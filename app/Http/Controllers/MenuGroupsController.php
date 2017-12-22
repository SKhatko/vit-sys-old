<?php namespace App\Http\Controllers;

use App\Course;
use App\Misc;
use App\MenuItem;
use App\MenuGroup;
use App\MenuCategory;
use App\MenuGroupTranslation;
use App\MenuLanguage;
use App\Tree;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MenuSingleton;

use Illuminate\Http\Request;

class MenuGroupsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('restaurant.menus');

        $pageName = 'menu-groups';

        $groups = MenuGroup::ordered()->withCount('courses', 'items')->with('courses')->get();

        $settings = MenuSingleton::getInstance()->getTempParams();

        $menuLanguages = MenuLanguage::all();

//        $language = MenuLanguage::getDefaultLanguage();
        $language = \App\Config::$language;

        return view('restaurant.menu_groups.index')->with([
            'title' => $title,
            'groups' => $groups,
            'settings' => $settings,
            'menuLanguages' => $menuLanguages,
            'language' => $language,
            'pageName' => $pageName
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('restaurant.new_menu');

        $pageName = 'menu-groups';

        $categories = MenuCategory::ordered()->with(['menu_items', 'translations'])->get()->getDictionary();

        $categoriesTree = Tree::createFromArray($categories);

        $items = MenuItem::ordered()->get();

        //$menuTranslationLanguages = Misc::getMenuTranslationLanguages();
        $menuLanguages = MenuLanguage::all();

        $language = \App\Config::$language;

        return view('restaurant.menu_groups.create')->with([
            'title' => $title,
            'pageName' => $pageName,
            'categoriesTree' => $categoriesTree,
            'items' => $items,
            'menuLanguages' => $menuLanguages,
            'language' => $language,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $quantities = $request->input('quantities');
        $items = $request->input('items');

        if (!$items && !$quantities) {
            //do noth (empty menu groups are allowed)
        } else if ($this->validateMenuGroupFormArrays($quantities, $items) !== true) {
            return redirect()->back();
        }

        if ($request->input('price')) {
            if (strpos($request->input('price'), ',') !== false) {
                $price = str_replace(',', '.', $request->input('price'));
                $request->merge([
                    'price' => $price
                ]);
            }
        }

        //handle checkboxes data
        $request->merge(['online_shown' => (bool)$request->input('online_shown')]);
        $request->merge(['preorders_shown' => (bool)$request->input('preorders_shown')]);

        $group = MenuGroup::create($request->all());

        foreach ($items as $key => $itemValues) {

            $course = Course::create([
                'group_id' => $group->id,
                'quantity' => $quantities[$key],
                'order_num' => $key + 1,
            ]);

            $course->menu_items()->attach($itemValues);
        }

        //handle translations
        $this->insertTranslations($group->id, $request->input('names'), $request->input('descriptions'));

        session()->flash('flash_message', trans('restaurant.menu_group_created_successfully_msg_with_name', ['name' => $group->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuGroupsController@index');
    }


    private function insertTranslations($groupId, $names, $descriptions, $sync = false)
    {

        $menuTranslationLanguages = MenuLanguage::all();

        if ($names && is_array($names) && count($names)) {

            if ($sync) {
                //delete old translations before inserting new ones
                $query = MenuGroupTranslation::where('group_id', '=', $groupId)->where(function ($query) use ($menuTranslationLanguages) {
                    $query->whereNull('name'); //always false (only for chaining ors)
                    foreach ($menuTranslationLanguages as $language) {
                        $query->orWhere('language', '=', $language->language);
                    }
                })->delete();
            }

            foreach ($menuTranslationLanguages as $language) {
                $lang = $language->language;
                if (isset($names[$lang])) {
                    $thisDescription = (isset($descriptions[$lang]) && !empty($descriptions[$lang])) ? $descriptions[$lang] : NULL;
                    MenuGroupTranslation::create([
                        'group_id' => $groupId,
                        'language' => $lang,
                        'name' => $names[$lang],
                        'description' => $thisDescription
                    ]);
                }
            }
        }
    }


    public function order(Request $request)
    {
        $sortArr = $request->all();
        $orderNum = 1;

        foreach ($sortArr as $key => $groupId) {
            $menu = MenuGroup::findOrFail($groupId[0]);
            $menu->update([
                'order_num' => $orderNum,
            ]);
            $orderNum++;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

        $title = trans('restaurant.edit_menu');

        $pageName = 'menu-groups';

        $categories = MenuCategory::ordered()->with(['menu_items', 'translations'])->get()->getDictionary();

        $group = MenuGroup::findOrFail($id);

        $categoriesTree = Tree::createFromArray($categories);

        $items = MenuItem::ordered()->get();

        $menuLanguages = MenuLanguage::all();

//        $language = session()->has('menu.language') ? session()->get('menu.language') : MenuLanguage::getDefaultLanguage();
        $language = \App\Config::$language;

        return view('restaurant.menu_groups.edit')->with([
            'title' => $title,
            'pageName' => $pageName,
            'group' => $group,
            'items' => $items,
            'menuLanguages' => $menuLanguages,
            'language' => $language,
            'categoriesTree' => $categoriesTree
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $quantities = $request->input('quantities');
        $items = $request->input('items');

        if (!$items && !$quantities) {
            $items = [];
            $quantities = [];
            //do noth (empty menu groups are allowed)
        } else if ($this->validateMenuGroupFormArrays($quantities, $items) !== true) {
            return redirect()->back();
        }

        if ($request->input('price')) {
            if (strpos($request->input('price'), ',') !== false) {
                $price = str_replace(',', '.', $request->input('price'));
                $request->merge([
                    'price' => $price
                ]);
            }
        }

        //handle checkboxes data
        $request->merge(['online_shown' => (bool)$request->input('online_shown')]);
        $request->merge(['preorders_shown' => (bool)$request->input('preorders_shown')]);

        $group = MenuGroup::findOrFail($id);
        $group->update($request->all());

        foreach ($group->courses as $course) {
            $course->menu_items()->sync([]);
            //@TODO
            //Instead of doing courses num * 1 queries, we can get IDs of all
            //courses, and then delete all records for these courses in one query
        }
        $group->courses()->delete();


        foreach ($items as $key => $itemValues) {

            $course = Course::create([
                'group_id' => $group->id,
                'quantity' => $quantities[$key],
                'order_num' => $key + 1,
            ]);

            $course->menu_items()->attach($itemValues);
        }

        /*** handle translations ***/
        $this->insertTranslations($id, $request->input('names'), $request->input('descriptions'), true);
        /***************************/

        return redirect()->action('MenuGroupsController@index');
    }

    /**
     * Validates arrays in menu groups form. Arrays are passed by reference.
     * Also sets session message on invalid input
     *
     * @param array $quantities , array $items
     * @return false on invalid input, true on successful validation
     */
    private function validateMenuGroupFormArrays(&$quantities, &$items = NULL)
    {

        //check that we have at least 1 course, and both quantities and items arrays match in length
        if (!is_array($quantities) || !is_array($items) || !count($quantities) || !count($items)) {

            session()->flash('flash_message', trans('general.error_occurred'));
            session()->flash('flash_message_type', 'alert-danger');

            return false;
        }

        //In case front-end validation is not working / not supported
        foreach ($quantities as $key => $quantity) {
            if ((int)$quantity < 0) {
                session()->flash('flash_message', trans('restaurant.invalid_quantities_error_msg'));
                session()->flash('flash_message_type', 'alert-danger');

                return false;

            } else if ((int)$quantity == 0 || !isset($items[$key]) || !count($items[$key])) {
                unset($quantities[$key]);
                unset($items[$key]);
            }
        }
        $quantities = array_values($quantities);
        $items = array_values($items);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $group = MenuGroup::findOrFail($id);
        $groupName = $group->name;

        $group->delete();
        MenuGroupTranslation::where('group_id', '=', $id)->delete();

        session()->flash('flash_message', trans('restaurant.menu_group_deleted_successfully_msg_with_name', ['name' => $group->name]));
        session()->flash('flash_message_type', 'alert-danger');

        return redirect()->action('MenuGroupsController@index');
    }

}
