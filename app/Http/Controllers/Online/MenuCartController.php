<?php namespace App\Http\Controllers\Online;

use App\MenuGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\MenuItem;

use App\MenuCart;

use DB;

class MenuCartController extends Controller
{
    public function getCart()
    {
        return MenuCart::getCart();
    }

    public function addItem($id)
    {

        $menuItem = MenuItem::with('translations')->findOrFail($id);
        return MenuCart::addItem($menuItem);
    }

    public function deductItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        return MenuCart::addItem($menuItem, -1);
    }

    public function removeItem($id)
    {

        $menuItem = MenuItem::with('translations')->findOrFail($id);
        return MenuCart::removeItem($menuItem);
    }

    public function addGroup(Request $request, $id)
    {

        /*
        $this->validate($request, [
            'items'	=>	'required',
        ]);
        */

        $itemsInput = NULL;
        $items = collect([]);
        if ($request->input('items')) {
            $itemsInput = $request->input('items');
        }
        if (is_array($itemsInput) && count($itemsInput)) {
            $orderedIds = implode(',', $request->input('items'));
            $items = MenuItem::with(['translations'])->orderByRaw(DB::raw("FIELD(id, $orderedIds)"))->findMany($request->items);
        }


        $group = MenuGroup::with(['translations'])->findOrFail($id);
        return MenuCart::addGroup($group, $items);
    }

    public function incrementGroup($key)
    {
        return MenuCart::incrementGroup($key);
    }

    public function deductGroup($key)
    {
        return MenuCart::incrementGroup($key, -1);
    }

    public function removeGroup($key)
    {
        return MenuCart::removeGroup($key);
    }

    public function clearCart()
    {
        return MenuCart::clearCart();
    }
}
