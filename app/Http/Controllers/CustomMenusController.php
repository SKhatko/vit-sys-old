<?php

namespace App\Http\Controllers;

use App\MenuGroup;
use Illuminate\Http\Request;

use App\CustomMenu;
use App\MenuCategory;
use App\MenuLanguage;

class CustomMenusController extends Controller
{

    public function index()
    {

        $title = trans('restaurant.custom_menus');
        $pageName = 'menus';

        $language = MenuLanguage::getDefaultLanguage();
        $customMenus = CustomMenu::ordered()->displayed()->get();

        return view('restaurant.custom_menus.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'language' => $language,
            'customMenus' => $customMenus
        ]);
    }

    public function create()
    {
        $title = trans('restaurant.new_custom_menu');
        $pageName = 'menus';

        $categories = MenuCategory::with('menu_items')->ordered()->get();

        $menuGroups = MenuGroup::ordered()->get();

        $menuLanguages = MenuLanguage::all();

        $language = session()->has('menu.language') ? session()->get('menu.language') : MenuLanguage::getDefaultLanguage();

        return view('restaurant.custom_menus.create')->with([
            'title' => $title,
            'pageName' => $pageName,
            'categories' => $categories,
            'language' => $language,
            'menuLanguages' => $menuLanguages,
            'menuGroups' => $menuGroups
        ]);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:75',
        ]);

        if (!$request->input('items') || !is_array($request->input('items'))) {
            $request->merge([
                'items' => []
            ]);
        }

        $customMenu = CustomMenu::create($request->all());

        $customMenu->items()->attach($request->input('items'));

        $customMenu->menu_groups()->attach($request->input('menu_groups'));

        session()->flash('flash_message', trans('restaurant.custom_menu_created_successfully_msg_with_name', ['name' => $customMenu->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('CustomMenusController@index');
    }

    public function edit($id)
    {
        $title = trans('restaurant.edit_custom_menu');

        $pageName = 'menus';

        $categories = MenuCategory::with('menu_items')->ordered()->get();

        $customMenu = CustomMenu::with('menu_items')->findOrFail($id);

        $menuGroups = MenuGroup::ordered()->get();

        $menuLanguages = MenuLanguage::all();

        $language = \App\Config::$language;

        return view('restaurant.custom_menus.edit')->with([
            'title' => $title,
            'pageName' => $pageName,
            'categories' => $categories,
            'menuLanguages' => $menuLanguages,
            'language' => $language,
            'customMenu' => $customMenu,
            'menuGroups' => $menuGroups
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:75',
        ]);

        if (!$request->input('items') || !is_array($request->input('items'))) {
            $request->merge([
                'items' => []
            ]);
        }

        $customMenu = CustomMenu::findOrFail($id);

        $customMenu->update($request->all());

        $customMenu->items()->sync($request->input('items'));

        $customMenu->menu_groups()->sync($request->input('menu_groups'));

        return redirect()->action('CustomMenusController@index');
    }

    public function destroy($id)
    {

        $customMenu = CustomMenu::findOrFail($id);

        $customMenuName = $customMenu->name;

        $customMenu->menu_items()->sync([]);
        $customMenu->menu_groups()->sync([]);
        $customMenu->reservation_configurations()->update([
            'custom_menu_id' => NULL
        ]);

        $customMenu->delete();

        session()->flash('flash_message', trans('restaurant.custom_menu_deleted_successfully_msg_with_name', ['name' => $customMenuName]));
        session()->flash('flash_message_type', 'alert-danger');

        return redirect()->action('CustomMenusController@index');
    }

    public function order(Request $request)
    {
        $sortArr = $request->all();
        $orderNum = 1;

        foreach ($sortArr as $key => $menuId) {
            $menu = CustomMenu::findOrFail($menuId[0]);
            $menu->update([
                'order_num' => $orderNum,
            ]);
            $orderNum++;
        }
    }

}
