<?php namespace App\Http\Controllers\Online;

use App\Reservation;
use App\Preorder;
use App\MenuCategory;
use App\Tree;

use App\MenuCart;

use App\MenuItem;
use App\MenuGroup;

use App\MenuLanguage;

use App\Misc;

use App\PreordersConfig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class PreordersController extends Controller
{

    // validate initial session
    private function validateSession()
    {
        return session()->has('preorder.identifier') ||
        session()->has('preorder.reservation_id') ||
        session()->has('preorder.token');
    }

    private function isInternal()
    {
        return (session()->has('preorder.internal') && session()->get('preorder.internal')) ? true : false;
    }

    // validate newly created user in session
    private function validateNewSession()
    {
        return (session()->has('preorder.new') && session()->get('preorder.new') === true && session()->has('preorder.name'));
    }

    //void
    private function setPreorderId($id, $name)
    {
        session()->forget('preorder.new');
        session()->forget('preorder.name');
        session()->put('preorder.id', $id);
    }

    private function getPreorderId()
    {
        return session()->get('preorder.id');
    }

    private function checkOutdated($reservation)
    {

        $hoursLimit = ($reservation->reservation_configuration) ?
            $reservation->reservation_configuration->hours_limit : PreordersConfig::getInstance()->hours_limit;

        $now = date("Y-m-d H:i:s");
        $hoursDiff = round((strtotime($now) - strtotime($reservation->date . ' ' . $reservation->time)) / 3600, 1);

        $hoursLimit = 0 - $hoursLimit;

        if ($hoursDiff >= $hoursLimit) {
            return true;
        }
        return false;
    }

    private function checkInternal()
    {
        return (session()->has('preorder.internal') && session()->get('preorder.internal'));
    }

    public function index($identifier, $token)
    {

        $reservation = Reservation::with('reservation_configuration')->identifier($identifier)->urlToken($token)->first();
        if (!$reservation) {
            return redirect()->route('preorder.error', ['invalid']);
        }

        $hoursLimit = ($reservation->reservation_configuration) ?
            $reservation->reservation_configuration->hours_limit : PreordersConfig::getInstance()->hours_limit;

        $outdated = $this->checkOutdated($reservation);

        //clear old session
        session()->forget('preorder');
        MenuCart::clearCart();

        session()->put('preorder.identifier', $identifier);
        session()->put('preorder.reservation_id', $reservation->id);
        session()->put('preorder.hours_limit', $hoursLimit);
        session()->put('preorder.token', $token);

        $preorders = $reservation->preorders()->pluck('name', 'id')->toArray();
        if (count($preorders)) {
            $preorders = ["" => trans('general.please_choose')] + $preorders;
        }

        return view('online.preorder.index')->with([
            'reservation' => $reservation,
            'identifier' => $identifier,
            'preorders' => $preorders,
            'outdated' => $outdated,
            'hoursLimit' => $hoursLimit,
        ]);
    }


    public function create(Request $request)
    {

        if (!$this->validateSession()) {
            return redirect()->route('preorder.error', ['unidentified']);
        }

        $this->validate($request, [
            'name' => 'required'
        ]);

        $reservationId = session()->get('preorder.reservation_id');
        //$reservation = Reservation::with('reservation_configuration')->findOrFail($reservationId);

        $name = $request->input('name');

        $existing = Preorder::where('reservation_id', '=', $reservationId)->name($name)->first();
        if ($existing) {

            session()->flash('flash_message', trans('online.preorder_name_exists_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        session()->put('preorder.new', true);
        session()->put('preorder.name', $name);
        session()->forget('preorder.id');

        MenuCart::clearCart();

        return redirect()->action('Online\PreordersController@menu');
    }

    public function setId(Request $request)
    {

        $this->validate($request, [
            'id' => 'numeric|required'
        ]);

        $preorderId = (int)$request->input('id');
        $preorder = Preorder::findOrFail($preorderId);

        $reservationId = session()->get('preorder.reservation_id');
        if ($preorder->reservation_id != $reservationId) {
            throw new \Exception('Error occurred trying to set preorder ID');
        }

        $this->setPreorderId($preorderId, $preorder->name);

        MenuCart::loadCart($this->parsePreorderCartData($preorder));

        return redirect()->action('Online\PreordersController@menu');
    }

    private function parsePreorderCartData($preorder)
    {

        $items = $preorder->menu_items()->with(['translations', 'allergies'])->get();
        $groups = $preorder->groups()->with(['translations'])->get();

        $parsedData = ['items' => [], 'groups' => []];

        foreach ($items as $item) {
            $parsedData['items'][] = [
                'id' => $item->id,
                'item' => $item,
                'quantity' => $item->pivot->quantity,
            ];
        }


        foreach ($groups as $group) {
            $groupItems = [];
            if ($group->pivot->items) {
                $orderedIds = $group->pivot->items;
                $groupItems = MenuItem::with(['translations', 'allergies'])->orderByRaw(DB::raw("FIELD(id, $orderedIds)"))->findMany(explode(',', $group->pivot->items));
            }

            $parsedData['groups'][] = [
                'id' => $group->id,
                'group' => $group,
                'items' => $groupItems,
                'quantity' => $group->pivot->quantity
            ];
        }

        return $parsedData;
    }

    public function menu()
    {

        if (!$this->validateSession() || (!$this->isInternal() && !$this->getPreorderId() && !$this->validateNewSession())) {
            return redirect()->route('preorder.error', ['unidentified']);
        }

        $customMenu = NULL;
        $reservationId = session()->get('preorder.reservation_id');
        $reservation = Reservation::with('reservation_configuration.custom_menu.items')->findOrFail($reservationId);

        $hoursLimit = ($reservation->reservation_configuration) ?
            $reservation->reservation_configuration->hours_limit : PreordersConfig::getInstance()->hours_limit;

        $outdated = $this->checkOutdated($reservation);

        if ($reservation->reservation_configuration && $reservation->reservation_configuration->custom_menu) {
            $customMenu = $reservation->reservation_configuration->custom_menu;
        }


        if (!$customMenu) {
            //load default preorders menu
            Misc::setDataView('preorders'); //sets filter to menu_items relationship called below
            $categories = MenuCategory::ordered()->with(['menu_items.translations', 'menu_items.allergies', 'translations'])->get()->getDictionary();
            $menuGroups = MenuGroup::dataView()->get();
        } else {
            $categories = MenuCategory::ordered()->with(['menu_items.translations', 'menu_items.allergies', 'translations'])->get()->getDictionary();
            foreach ($categories as $category) {
                foreach ($category->menu_items as $key => $item) {
                    if (!$customMenu->items->contains('id', $item->id)) {
                        unset($category->menu_items[$key]);
                    }
                }
            }

            $menuGroups = MenuGroup::with(['courses.menu_items.translations', 'courses.menu_items.allergies', 'translations'])->get();

            foreach ($menuGroups as $key => $group) {
                if (!$customMenu->menu_groups->contains('id', $group->id)) {
                    unset($menuGroups[$key]);
                }
            }
        }

        $categoriesTree = Tree::createFromArray($categories);

        $menuLanguages = MenuLanguage::published()->get();
        $menuLanguage = session()->has('menu.language') ? session()->get('menu.language') : MenuLanguage::getDefaultLanguage();

        Misc::cleanCategoriesTree($categoriesTree, $menuLanguage);
        Misc::cleanMenuGroups($menuGroups, $menuLanguage);

        //get reservation preorder config
        $settings = Misc::getPreordersRenderSettings($reservation->reservation_configuration);

        $identifier = session()->get('preorder.identifier');
        $token = session()->get('preorder.token');
        $backPath = action('Online\PreordersController@index', [$identifier, $token]);

        return view('online.preorder.menu')->with([
            'categoriesTree' => $categoriesTree,
            'menuGroups' => $menuGroups,
            'menuLanguages' => $menuLanguages,
            'menuLanguage' => $menuLanguage,
            'settings' => $settings,
            'hoursLimit' => $hoursLimit,
            'outdated' => $outdated,
            'view' => 'menu',
            'backPath' => $backPath
        ]);
    }

    //checks if we have at least 1 item, or 1 group
    /*
    private function validateItemsAndGroups($items = NULL, $groups = NULL) {
        return ($items && is_array($items) && count($items)) ||
            ($groups && is_array($groups) && count($groups));
    }

    private function validateGroupsInputArrays($items = NULL, $quantities = NULL, $groupIds = NULL) {
        if (($items != null || $quantities != NULL || $groupIds) &&
            (!is_array($items) || !is_array($quantities) || !is_array($groupIds) || count($items) != count($quantities)|| count($items) != count($groupIds))) {

            return false;
        }

        return true;
    }
    */

    private function validateCartSubmission()
    {
        $cartData = MenuCart::getCart();

        $items = isset($cartData['items']) ? $cartData['items'] : NULL;
        $groups = isset($cartData['groups']) ? $cartData['groups'] : NULL;

        return ($items && is_array($items) && count($items)) ||
        ($groups && is_array($groups) && count($groups));
    }

    public function submit(Request $request)
    {

        if (!$this->validateSession() || (!$this->getPreorderId() && !$this->validateNewSession())) {

            return redirect()->route('preorder.error', ['invalid']);
        }

        if (!$this->validateCartSubmission()) {
            session()->flash('flash_message', trans('online.preorder_invalid_cart_data_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $name = session()->get('preorder.name');
        $reservationId = session()->get('preorder.reservation_id');
        $reservation = Reservation::findOrFail($reservationId);

        if ($this->checkOutdated($reservation)) {
            return redirect()->route('preorder.error', ['outdated']);
        }

        if ($this->checkInternal()) {
            return 'This is a system a link. Order can not be submitted';
        }

        return redirect()->action('Online\PreordersController@confirm');

    }

    public function confirm()
    {

        if (!$this->validateSession() || (!$this->getPreorderId() && !$this->validateNewSession())) {

            return redirect()->route('preorder.error', ['invalid']);
        }

        if (!$this->validateCartSubmission()) {
            session()->flash('flash_message', trans('online.preorder_invalid_cart_data_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->action('Online\PreordersController@menu');
        }

        //check outdated
        $identifier = session()->get('preorder.identifier');
        $reservationId = session()->get('preorder.reservation_id');
        $reservation = Reservation::findOrFail($reservationId);

        if ($this->checkOutdated($reservation)) {
            return redirect()->route('preorder.error', ['outdated']);
        }

        $cartData = MenuCart::getCart();
        $menuLanguage = session()->has('menu.language') ? session()->get('menu.language') : MenuLanguage::getDefaultLanguage();

        $preorder = NULL;
        $submitBtnText = "";
        if ($this->getPreorderId()) {
            $preorder = Preorder::find($this->getPreorderId());
            $submitBtnText = trans('online.update_preorder');
        } else {
            $preorder = new Preorder();
            $preorder->name = session()->get('preorder.name');
            $preorder->notes = NULL;
            $submitBtnText = trans('online.place_preorder');
        }

        $backPath = action('Online\PreordersController@menu');

        return view('online.preorder.confirm')->with([
            'cartData' => $cartData,
            'menuLanguage' => $menuLanguage,
            'identifier' => $identifier,
            'reservation' => $reservation,
            'preorder' => $preorder,
            'submitBtnText' => $submitBtnText,
            'view' => 'confirm',
            'backPath' => $backPath
        ]);
    }

    public function postConfirm(Request $request)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

        //validate session and menu data
        if (!$this->validateSession() || (!$this->getPreorderId() && !$this->validateNewSession())) {

            return redirect()->route('preorder.error', ['invalid']);
        }

        if (!$this->validateCartSubmission()) {
            session()->flash('flash_message', trans('online.preorder_invalid_cart_data_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->action('Online\PreordersController@menu');
        }

        $reservationId = session()->get('preorder.reservation_id');
        $reservation = Reservation::findOrFail($reservationId);

        if ($this->checkOutdated($reservation)) {
            return redirect()->route('preorder.error', ['outdated']);
        }

        $identifier = session()->get('preorder.identifier');
        $token = session()->get('preorder.token');


        //handle data
        $name = $request->input('name');
        $notes = $request->input('notes') ? $request->input('notes') : NULL;

        $preorder = NULL;
        if ($this->getPreorderId()) {

            $preorder = Preorder::find($this->getPreorderId());
            if (strcmp($preorder->name, $name)) {
                $nameExists = $reservation->preorders()->name($name)->first();
                if ($nameExists) {
                    session()->flash('flash_message', trans('online.preorder_name_exists_error_msg'));
                    session()->flash('flash_message_type', 'alert-danger');

                    return redirect()->back();
                }
            }

            if (strcmp($preorder->name, $name) || strcmp($preorder->notes, $notes)) {

                $preorder->name = $name;
                $preorder->notes = $notes;
                $preorder->save();
            }

            $successMsg = trans('online.preorder_update_success_msg');
        } else {
            
            $nameExists = $reservation->preorders()->name($name)->first();
            //$nameExists = Preorder::where('reservation_id', '=', $reservationId)->name($name)->first();
            if ($nameExists) {
                session()->flash('flash_message', trans('online.preorder_name_exists_error_msg'));
                session()->flash('flash_message_type', 'alert-danger');

                return redirect()->back();
            }

            $preorder = Preorder::create([
                'name' => $name,
                'reservation_id' => $reservationId,
                'notes' => $notes
            ]);

            $successMsg = trans('online.preorder_creation_success_msg');
        }

        $cart = MenuCart::getCart();

        $cleanedItemsData = [];
        if (isset($cart['items']) && is_array($cart['items']) && count($cart['items'])) {
            foreach ($cart['items'] as $itemRecord) {
                $cleanedItemsData[$itemRecord['item']->id] = ['quantity' => $itemRecord['quantity']];
            }
        }
        $preorder->items()->sync($cleanedItemsData);


        $cleanedGroupsData = [];
        $preorder->groups()->detach();

        if (isset($cart['groups']) && is_array($cart['groups']) && count($cart['groups'])) {

            $groupsData = $cart['groups'];
            foreach ($groupsData as $id => $data) {

                DB::connection('tenant')->table('group_preorder')->insert([
                    'preorder_id' => $preorder->id,
                    'group_id' => $data['id'],
                    'items' => implode(',', $data['item_ids']),
                    'quantity' => $data['quantity']
                ]);
            }
        }

        if (!$reservation->has_preorders) {
            $reservation->has_preorders = true;
            $reservation->save();
        }

        session()->flash('flash_message', $successMsg);
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('Online\PreordersController@index', [$identifier, $token]);


    }

    public function errorPage($errorType = 'invalid')
    {
        $errorType = in_array($errorType, ['invalid', 'unidentified', 'outdated']) ? $errorType : 'invalid';

        return view('online.preorder.error')->with([
            'errorType' => $errorType,
            'title' => \App\Config::$restaurant_name
        ]);
    }

    public function delete(Request $request)
    {

        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        $preorderId = (int)$request->input('id');
        $preorder = Preorder::findOrFail($preorderId);

        $reservationId = session()->get('preorder.reservation_id');
        if ($preorder->reservation_id != $reservationId) {
            throw new \Exception('Error occurred trying to set preorder ID');
        }

        $reservation = Reservation::findOrFail($reservationId);
        $outdated = $this->checkOutdated($reservation);
        if ($this->checkOutdated($reservation)) {
            return redirect()->route('preorder.error', ['outdated']);
        }

        $this->setPreorderId($preorderId, $preorder->name);

        $identifier = session()->get('preorder.identifier');
        $token = session()->get('preorder.token');
        $backPath = action('Online\PreordersController@index', [$identifier, $token]);

        return view('online.preorder.delete')->with([
            'preorder' => $preorder,
            'view' => 'delete',
            'backPath' => $backPath
        ]);

    }

    public function destroy(Request $request)
    {

        $preorderId = $this->getPreorderId();
        $preorder = Preorder::findOrFail($preorderId);

        $preorder->items()->detach();
        $preorder->groups()->detach();

        $preorderName = $preorder->name;
        $preorder->delete();

        $reservationId = session()->get('preorder.reservation_id');
        $reservation = Reservation::findOrFail($reservationId);

        if ($this->checkOutdated($reservation)) {
            return redirect()->route('preorder.error', ['outdated']);
        }

        if ($reservation->preorders()->count() == 0) {
            $reservation->has_preorders = false;
            $reservation->save();
        }

        $identifier = session()->get('preorder.identifier');
        $token = session()->get('preorder.token');

        session()->flash('flash_message', trans('online.preorder_deletion_success_msg'));
        session()->flash('flash_message_type', 'alert-danger');

        return redirect()->action('Online\PreordersController@index', [$identifier, $token]);

    }

}
