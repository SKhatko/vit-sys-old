<?php namespace App;

use \App\Config as TenantConfig;
use \App\Preorder;

use File;
use Session;

class Misc
{

    public static $languages = [
        'de',
        'en',
        'es',
        'fr',
        'it',
        'nl',
        'ru',
        'tr',
    ];

    /** Data View Attribute **/
    private static $dataView = NULL;

    public static function setDataView($view)
    {

        if (self::$dataView != NULL) {
            //throw error. no 2 different views can be requested in same request session
            //@TODO
        }

        self::$dataView = $view;
    }

    public static function getDataView()
    {
        return self::$dataView;
    }

    /****************/


    public static function getPreordersRenderSettings($reservationConfig = NULL)
    {
        $settings = [];

        if ($reservationConfig == null) {
            $settings['display_images'] = (bool)\App\PreordersConfig::getInstance()->display_images;
            $settings['display_prices'] = (bool)\App\PreordersConfig::getInstance()->display_prices;
        } else {
            $settings['display_images'] = (bool)$reservationConfig->display_images;
            $settings['display_prices'] = (bool)$reservationConfig->display_prices;
        }
        $settings['display_allergies'] = true;

        return $settings;
    }

    public static function cleanCategoriesTree(&$categoriesTree = array(), $language = NULL)
    {

        foreach ($categoriesTree as $key => &$obj) {
            if (!self::countTreeNestedItems($obj, $language)) {
                unset($categoriesTree[$key]);
            }
        }
    }

    //recursive count of nested (translated - if language is set) items in categories tree
    private static function countTreeNestedItems(&$obj, $language = NULL)
    {

        if ($language) {
            if (!$obj['object']->translatedName($language)) {
                return 0;
            }

            $count = 0;
            foreach ($obj['object']->menu_items as $item) {
                if ($item->translatedName($language)) {
                    $count++;
                }
            }
        } else {
            $count = count($obj['object']->menu_items);
        }

        if (count($obj['children'])) {
            foreach ($obj['children'] as $key => &$nestedObj) {
                $subCount = self::countTreeNestedItems($nestedObj, $language);
                if ($subCount == 0) {
                    unset($obj['children'][$key]);
                }
                $count += $subCount;
            }
        }

        return $count;
    }

    public static function cleanMenuGroups(&$menuGroups, $language)
    {

        foreach ($menuGroups as $key => $menuGroup) {
            if (!$menuGroup->translatedName($language)) {
                unset($menuGroups[$key]);
            }
        }
    }

    public static function renderTreeSelect($name = "", $dataTree = array(), $selected = array(), $multiple = false, $emptyRow = false, $attributes = NULL, $emptyText = NULL)
    {

        $language = \App\Config::$default_menu_language;

        $html = '<select name="' . $name . '" ';

        if (is_array($attributes) && count($attributes)) {
            foreach ($attributes as $key => $value) {
                $html .= ' ' . $key . '="' . $value . '"';
            }
        }

        $html .= '>';

        if ($emptyRow) {
            $html .= '<option value="">' . $emptyText . '</option>';
        }

        $level = 0;


        while (count($dataTree)) {

            $item = array_shift($dataTree);

            if (is_array($item)) {
                if (count($item['children'])) {
                    array_unshift($dataTree, null);
                    $dataTree = array_merge($item['children'], $dataTree);

                    $html .= '<option value="' . $item['object']->id . '"';

                    if (in_array($item['object']->id, $selected)) {
                        $html .= ' selected';
                    }
                    $html .= '>';

                    for ($i = 0; $i < $level; $i++) {
                        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }

                    $html .= ' &#8618; ' . $item['object']->translatedName($language) . '</option>';

                    $level += 1;
                } else {
                    $html .= '<option value="' . $item['object']->id . '"';

                    if (in_array($item['object']->id, $selected)) {
                        $html .= ' selected';
                    }
                    $html .= '>';

                    for ($i = 0; $i < $level; $i++) {
                        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    $html .= ' &#8618; ' . $item['object']->translatedName($language) . '</option>';
                }
            } else if (is_null($item)) {
                $level -= 1;
            }
        }

        $html .= '</select>';

        echo $html;
    }

    public static function renderActionMenu($dataTree, $settings, $config, $menuGroups = NULL)
    {

        /** Print items settings **/
        $printItems = isset($config['print_items']) ? (bool)$config['print_items'] : true;
        $itemsPerRow = isset($config['items_per_row']) ? intval($config['items_per_row']) : 1;

        /** item printing settings **/
        $itemImages = (isset($settings['display_images']) && (bool)$settings['display_images']);
        $prices = (isset($settings['display_prices']) && (bool)$settings['display_prices']);
        $allergies = (isset($settings['display_allergies']) && (bool)$settings['display_allergies']);

        /** html settings **/
        $itemHolderClasses = isset($config['item_holder_classes']) ? $config['item_holder_classes'] : [];
        $clearOnCategoryClose = isset($config['clear_on_category_close']) ? (bool)$config['clear_on_category_close'] : true;
        $categoryOpener = isset($config['category_opener']) ? $config['category_opener'] : '<ul>';
        $categoryCloser = isset($config['category_closer']) ? $config['category_closer'] : '</ul>';
        $subCategoryOpener = isset($config['sub_category_opener']) ? $config['sub_category_opener'] : '<ul>';
        $subCategoryCloser = isset($config['sub_category_closer']) ? $config['sub_category_closer'] : '</ul>';
        $categoryHolderOpener = isset($config['category_holder_opener']) ? $config['category_holder_opener'] : '<div class="category-holder" id="cat-%id%" data-category-id="%id%">';
        $categoryHolderCloser = isset($config['category_holder_closer']) ? $config['category_holder_closer'] : '</div>';

        $courseOpener = isset($config['course_opener']) ? $config['course_opener'] : '<div class="course-holder" id="course-%id%" data-course-id="%id%">';
        $courseCloser = isset($config['course_closer']) ? $config['course_closer'] : '</div>';

        //language
        $language = isset($config['language']) ? $config['language'] : \App\MenuLanguage::getDefaultLanguage();

        /***** Implementation ****/
        echo $categoryOpener;

        $level = 1;

        while (count($dataTree)) {

            $item = array_shift($dataTree);

            if (is_array($item) && !$item['object']->translatedName($language)) {
                //echo 'continue';
                continue;
            }

            if (is_array($item) && count($item['children'])) {
                array_unshift($dataTree, null);
                $dataTree = array_merge($item['children'], $dataTree);

                echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                //check if parent category has also items next to categories. If so, print them
                if ($printItems && count($item['object']->menu_items)) {

                    echo self::renderActionMenuItems($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);

                }

                echo $subCategoryOpener;

                $level += 1;
            } else if (is_null($item)) {
                //we reached end of sub categories, starting new 'parent category'
                echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                echo $subCategoryCloser;
                echo $categoryHolderCloser;

                $level -= 1;
            } else {
                //print category that has no sub categories
                if ($level > 1 && !count($item['object']->menu_items)) {
                    //do nothing
                } else {

                    if ($level <= 2) {
                        echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                        echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                        if ($printItems) {
                            echo self::renderActionMenuItems($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }
                    } else {
                        if ($printItems) {
                            echo self::renderActionMenuItems($item['object'], $item['object']->translatedName($language), $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }
                    }

                    if ($level <= 2) {
                        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                        echo $categoryHolderCloser;
                    }
                }
            }
        }


        //print menu groups
        if (\App\MenuSingleton::getInstance()->menu_title($language)) {

            if ($menuGroups && count($menuGroups)) {
                echo '<div class="menu-group-holder">';
                echo $categoryOpener;
                echo str_replace('%id%', 'menus', $categoryHolderOpener);
                //echo '<h1>Menus</h1>';

                foreach ($menuGroups as $menuGroup) {

                    echo $subCategoryOpener;

                    echo str_replace('%id%', $menuGroup->id, $categoryHolderOpener);
                    echo '<span class="menu-prefix"></span>';

                    echo '<h1><span class="menu-name">' . $menuGroup->translatedName($language) . '</span>';
                    if ($prices && $menuGroup->price && $menuGroup->price > 0) {
                        echo '<span class="menu-price"> - ';
                        self::printCurrency();
                        echo self::formatDecimal($menuGroup->price) . '</span>';
                    }
                    echo '</h2>';

                    echo '<div class="menu-description">' . $menuGroup->translatedDescription($language) . '</div>';

                    echo '<div class="menu-content">';

                    $courseCounter = 0;
                    foreach ($menuGroup->courses as $course) {

                        $courseCounter++;

                        echo str_replace('%id%', 'course-' . $course->id, $courseOpener);
                        echo '<span class="course-prefix"></span>';

                        echo '<h2><span class="course-name">' . trans('restaurant.course_with_number', ['number' => $courseCounter]) . '</span> <span class="course-quantity">(' . trans('online.course_select_quantity_with_quantity', ['quantity' => $course->quantity]) . ')</span></h2>';

                        echo '<div class="course-content" data-course-quantity="' . $course->quantity . '">';

                        $structuredItems = [];
                        $categories = [];
                        foreach ($course->items as $item) {
                            $structuredItems[$item->category_id][] = $item;
                            if (!array_key_exists($item->category_id, $categories)) {
                                $categories[$item->category_id] = $item->category;
                            }
                        }

                        //print items with their categories
                        foreach ($categories as $id => $category) {
                            //echo '<h2>'.$category->translatedName($language).'</h2>';
                            self::renderActionMenuItems($structuredItems[$id], $category->translatedName($language), $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }

                        echo '</div>';

                        echo '<span class="course-suffix"></span>';
                        echo $courseCloser;
                    }

                    echo '</div>';

                    echo '<div class="action-btn menu-action-btn">';
                    echo '<a href="javascript:;" class="action-btn-add menu-action-btn-add">' . trans('online.add_button') . '</a>';
                    echo '</div>';

                    echo '<span class="menu-suffix"></span>';

                    echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                    echo $categoryHolderCloser;

                    echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                    echo $subCategoryCloser;

                }

                echo $categoryHolderCloser;
                echo $categoryCloser;
                echo '</div>';
            }
        }


        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
        echo $categoryCloser;

    }

    /** Render template A **/
    /** Config Parameter is an array for compatible changes in the future, adding parameters can be done without breaking the function use **/
    public static function renderOnlineMenuA($dataTree, $settings, $config, $temp = false)
    {

        /** Print items settings **/
        $printItems = isset($config['print_items']) ? (bool)$config['print_items'] : true;
        $itemsPerRow = isset($config['items_per_row']) ? intval($config['items_per_row']) : 1;

        /** item printing settings **/
        $itemImages = $temp || (isset($settings['display_images']) && (bool)$settings['display_images']);
        $prices = $temp || (isset($settings['display_prices']) && (bool)$settings['display_prices']);
        $allergies = $temp || (isset($settings['display_allergies']) && (bool)$settings['display_allergies']);


        /** html settings **/
        $itemHolderClasses = isset($config['item_holder_classes']) ? $config['item_holder_classes'] : [];
        $clearOnCategoryClose = isset($config['clear_on_category_close']) ? (bool)$config['clear_on_category_close'] : true;
        $categoryOpener = isset($config['category_opener']) ? $config['category_opener'] : '<ul>';
        $categoryCloser = isset($config['category_closer']) ? $config['category_closer'] : '</ul>';
        $subCategoryOpener = isset($config['sub_category_opener']) ? $config['sub_category_opener'] : '<ul>';
        $subCategoryCloser = isset($config['sub_category_closer']) ? $config['sub_category_closer'] : '</ul>';
        $categoryHolderOpener = isset($config['category_holder_opener']) ? $config['category_holder_opener'] : '<div class="category-holder" id="cat-%id%" data-category-id="%id%">';
        $categoryHolderCloser = isset($config['category_holder_closer']) ? $config['category_holder_closer'] : '</div>';
        $courseOpener = isset($config['course_opener']) ? $config['course_opener'] : '<div class="course-holder" id="course-%id%" data-course-id="%id%">';
        $courseCloser = isset($config['course_closer']) ? $config['course_closer'] : '</div>';

        $language = isset($config['language']) ? $config['language'] : \App\Config::$language;

        /***** Implementation ****/
        echo $categoryOpener;

        $level = 1;

        while (count($dataTree)) {

            $item = array_shift($dataTree);

            if (is_array($item) && !$item['object']->translatedName($language)) {
                continue;
            }

            if (is_array($item) && count($item['children'])) {
                array_unshift($dataTree, null);
                $dataTree = array_merge($item['children'], $dataTree);

                echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                //check if parent category has also items next to categories. If so, print them
                if ($printItems && count($item['object']->menu_items)) {
                    echo self::renderOnlineMenuItemsA($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                }

                echo $subCategoryOpener;

                $level += 1;
            } else if (is_null($item)) {
                //we reached end of sub categories, starting new 'parent category'
                echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                echo $subCategoryCloser;
                echo $categoryHolderCloser;

                $level -= 1;
            } else {
                //print category that has no sub categories
                if ($level > 1 && !count($item['object']->menu_items)) {
                    //do nothing
                } else {

                    if ($level <= 2) {
                        echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                        echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                        if ($printItems) {
                            echo self::renderOnlineMenuItemsA($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }
                    } else {
                        if ($printItems) {
                            echo self::renderOnlineMenuItemsA($item['object'], $item['object']->translatedName($language), $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }
                    }

                    if ($level <= 2) {
                        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                        echo $categoryHolderCloser;
                    }
                }
            }
        }


        //print menu groups (menus)
        if (($temp || intval($settings['display_menus']) !== 0) && \App\MenuSingleton::getInstance()->menu_title($language)) {
            $menuGroups = \App\MenuGroup::dataView()->with(['courses.items.translations', 'courses.items.allergies', 'translations'])->ordered()->get();

            if (count($menuGroups)) {
                echo '<div class="menu-holder">';
                echo $categoryOpener;
                echo str_replace('%id%', 'menus', $categoryHolderOpener);
                //echo '<h1>Menus</h1>';

                foreach ($menuGroups as $menuGroup) {


                    echo $subCategoryOpener;

                    echo str_replace('%id%', 'menu-' . $menuGroup->id, $categoryHolderOpener);
                    echo '<span class="menu-prefix"></span>';

                    echo '<h1><span class="menu-name">' . $menuGroup->translatedName($language) . '</span>';
                    if ($prices && $menuGroup->price && $menuGroup->price > 0) {
                        echo '<span class="menu-price"> - ';
                        self::printCurrency();
                        echo self::formatDecimal($menuGroup->price) . '</span>';
                    }
                    echo '</h2>';

                    echo '<div class="menu-description">' . $menuGroup->translatedDescription($language) . '</div>';

                    echo '<div class="menu-content">';

                    $courseCounter = 0;
                    foreach ($menuGroup->courses as $course) {

                        $courseCounter++;

                        echo str_replace('%id%', 'course-' . $course->id, $courseOpener);
                        echo '<span class="course-prefix"></span>';

                        echo '<h2><span class="course-name">' . trans('restaurant.course_with_number', ['number' => $courseCounter]) . '</span> <span class="course-quantity">(' . trans('online.course_select_quantity_with_quantity', ['quantity' => $course->quantity]) . ')</span></h2>';


                        echo '<div class="course-content">';

                        $structuredItems = [];
                        $categories = [];
                        foreach ($course->items as $item) {
                            $structuredItems[$item->category_id][] = $item;
                            if (!array_key_exists($item->category_id, $categories)) {
                                $categories[$item->category_id] = $item->category;
                            }
                        }

                        //print items with their categories
                        foreach ($categories as $id => $category) {
                            //echo '<h2>'.$category->translatedName($language).'</h2>';
                            self::renderOnlineMenuItemsA($structuredItems[$id], $category->translatedName($language), $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                        }

                        echo '</div>';

                        echo '<span class="course-suffix"></span>';
                        echo $courseCloser;
                    }

                    /*
                    $structuredItems = [];
                    $categories = [];
                    foreach ($menuGroup->items as $item) {
                        $structuredItems[$item->category_id][] = $item;
                        if (!array_key_exists($item->category_id, $categories)) {
                            $categories[$item->category_id] = $item->category;
                        }
                    }

                    //print items with their categories
                    foreach ($categories as $id => $category) {
                        echo '<h2>'.$category->translatedName($language).'</h2>';
                        self::renderOnlineMenuItemsA($structuredItems[$id], null, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses, $config);
                    }
                    */

                    echo '</div>';

                    echo '<span class="menu-suffix"></span>';

                    echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                    echo $categoryHolderCloser;

                    echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                    echo $subCategoryCloser;

                }

                echo $categoryHolderCloser;
                echo $categoryCloser;
                echo '</div>';
            }
        }

        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
        echo $categoryCloser;

    }

    public static function renderOnlineMenu($dataTree = [], $printItems, $settings, $itemsPerRow = 2,
                                            $itemHolderClasses = [], $clearOnCategoryClose = true,
                                            $categoryOpener = '<ul>', $categoryCloser = '</ul>',
                                            $subCategoryOpener = '<ul>', $subCategoryCloser = '</ul>',
                                            $categoryHolderOpener = '<div class="category-holder" id="cat-%id%" data-category-id="%id%">', $categoryHolderCloser = '</div>')
    {

        $itemImages = $settings['display_images'];
        $prices = $settings['display_prices'];
        $allergies = $settings['display_allergies'];

        echo $categoryOpener;

        $level = 1;

        while (count($dataTree)) {

            $item = array_shift($dataTree);

            if (is_array($item) && count($item['children'])) {

                array_unshift($dataTree, null);
                $dataTree = array_merge($item['children'], $dataTree);

                echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                //check if parent category has also items next to categories. If so, print them
                if ($printItems && count($item['object']->menu_items)) {
                    echo self::renderOnlineMenuItems($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses);
                }

                echo $subCategoryOpener;

                $level += 1;
            } else if (is_null($item)) {
                //we reached end of sub categories, starting new 'parent category'
                echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                echo $subCategoryCloser;
                echo $categoryHolderCloser;

                $level -= 1;
            } else {
                //print category that has no sub categories
                if (count($item['object']->menu_items)) {

                    if ($level <= 2) {
                        echo str_replace('%id%', $item['object']->id, $categoryHolderOpener);
                        echo '<h' . $level . '>' . $item['object']->translatedName($language) . '</h' . $level . '>';

                        if ($printItems) {
                            echo self::renderOnlineMenuItems($item['object'], NULL, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses);
                        }
                    } else {
                        if ($printItems) {
                            echo self::renderOnlineMenuItems($item['object'], $item['object']->translatedName($language), $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses);
                        }
                    }

                    if ($level <= 2) {
                        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                        echo $categoryHolderCloser;
                    }
                }
            }
        }


        //print menu groups (menus)
        $menuGroups = \App\MenuGroup::with('items')->ordered()->get();

        if (count($menuGroups)) {
            echo $categoryOpener;
            echo str_replace('%id%', 'menus', $categoryHolderOpener);
            //echo '<h1>Menus</h1>';

            foreach ($menuGroups as $menuGroup) {
                echo $subCategoryOpener;

                echo str_replace('%id%', 'menu-' . $menuGroup->id, $categoryHolderOpener);
                echo '<span class="menu-prefix"></span>';

                echo '<h1><span class="menu-name">' . $menuGroup->translatedName($language) . '</span>';
                if ($prices && $menuGroup->price && $menuGroup->price > 0) {
                    echo '<span class="menu-price"> - ';
                    self::printCurrency();
                    echo self::formatDecimal($menuGroup->price) . '</span>';
                }
                echo '</h2>';

                echo '<div class="menu-description">' . $menuGroup->description . '</div>';

                echo '<div class="menu-content">';

                $structuredItems = [];
                $categories = [];
                foreach ($menuGroup->items as $item) {
                    $structuredItems[$item->category_id][] = $item;
                    if (!array_key_exists($item->category_id, $categories)) {
                        $categories[$item->category_id] = $item->category;
                    }
                }

                //print items with their categories
                foreach ($categories as $id => $category) {
                    echo '<h2>' . $category->name . '</h2>';
                    self::renderOnlineMenuItems($structuredItems[$id], null, $itemImages, $prices, $allergies, $itemsPerRow, $itemHolderClasses);
                }


                echo '</div>';

                echo '<span class="menu-suffix"></span>';

                echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                echo $categoryHolderCloser;

                echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
                echo $subCategoryCloser;
            }

            echo $categoryHolderCloser;
            echo $categoryCloser;

        }

        echo $clearOnCategoryClose ? '<div class="clear"></div>' : '';
        echo $categoryCloser;
    }

    public static function renderOnlineMenuItemsA($category, $prefix = NULL, $images = true, $prices = true, $allergies = true, $itemsPerRow, $itemHolderClasses, $config)
    {

        $language = isset($config['language']) ? $config['language'] : \App\Config::$language;

        if (!is_array($category)) {
            $menus = false;
            /*
            if ($allergies) {
                $items = $category->menu_items()->with(['allergies', 'translations'])->get();
            }
            else {
                $items = $category->menu_items()->with('translations')->get();
            }
            */
            $items = $category->menu_items;
        } else {
            $menus = true;
            $items = $category;
        }

        $rowCounter = 0;
        echo '<div class="items-row">';

        foreach ($items as $item) {

            if (!$item->translatedName($language)) {
                continue;
            }

            if ($rowCounter != 0 && $rowCounter % 2 == 0) {
                echo '<div class="clear"></div></div><div class="items-row">';
            }

            $rowCounter++;

            echo '<div class="menu-item-holder';
            foreach ($itemHolderClasses as $class) {
                echo ' ' . $class;
            }
            echo '">';

            echo '<span class="menu-item-prepended"></span>';

            if ($images && $item->image) {
                echo '<img src="' . asset($item->image) . '" alt="' . $item->translatedName($language) . '" />';
            }

            echo '<div class="menu-item-content">';

            if ($prefix) {
                echo '<span class="menu-item-name-prefix">' . $prefix . '</span> / ';
            }

            echo '<span class="menu-item-name">' . $item->translatedName($language) . '</span><span class="menu-item-name-suffix"></span><br>';


            echo '<span class="menu-item-description';
            if (!$item->translatedDescription($language)) {
                echo ' empty"';
            } else {
                echo '"';
            }
            echo '>' . $item->translatedDescription($language) . '</span>';

            if (!$menus && $prices && $item->price && $item->price > 0) {
                echo '<span class="menu-item-price">';
                self::printCurrency();

                echo self::formatDecimal($item->price) . '</span>';
            }

            if ($allergies && count($item->allergies)) {
                echo '<div class="allergy-icons">';

                foreach ($item->allergies as $allergy) {

                    $iconLink = "img/menu/allergies/" . $allergy->name . ".png";
                    $iconTitle = trans('allergies.' . $allergy->name);

                    echo '<span class="allergy-icon" title="' . $iconTitle . '">';
                    echo '<img src="' . asset($iconLink) . '" alt="' . $iconTitle . '" />';
                    echo '</span>';
                }

                echo '</div>';
            }

            echo '<span class="menu-item-appended"></span>';

            echo '</label></div>';

            echo '<div class="clear"></div>';
            echo '</div>';
        }

        echo '<div class="clear"></div>';
        echo '</div><!-- .items-row -->';
    }

    public static function renderActionMenuItems($category, $prefix = NULL, $images = true, $prices = true, $allergies = true, $itemsPerRow, $itemHolderClasses, $config)
    {

        $language = isset($config['language']) ? $config['language'] : \App\Config::$language;

        if (!is_array($category)) {
            $menus = false;
            $items = $category->menu_items;
        } else {
            $menus = true;
            $items = $category;
        }

        $rowCounter = 0;
        echo '<div class="items-row">';

        foreach ($items as $item) {

            if (!$item->translatedName($language)) {
                continue;
            }

            if ($rowCounter != 0 && $rowCounter % 2 == 0) {
                echo '<div class="clear"></div></div><div class="items-row">';
            }

            $rowCounter++;

            echo '<div class="menu-item-holder';
            foreach ($itemHolderClasses as $class) {
                echo ' ' . $class;
            }
            echo '" data-item-id="' . $item->id . '">';

            echo '<span class="menu-item-prepended"></span>';

            if ($images && $item->image) {
                echo '<img src="' . asset($item->image) . '" alt="' . $item->translatedName($language) . '" />';
            }

            echo '<div class="menu-item-content">';

            if ($menus) {
                $randomId = round(microtime(true) * 1000);
                echo '<label for="item-' . $randomId . '" class="item-label">';
                echo '<span class="item-checkbox"><input type="checkbox" id="item-' . $randomId . '" data-item-id="' . $item->id . '" name="item[]" value="true"></span>';
            }

            if ($prefix) {
                echo '<span class="menu-item-name-prefix">' . $prefix . '</span> / ';
            }

            echo '<span class="menu-item-name">' . $item->translatedName($language) . '</span><span class="menu-item-name-suffix"></span><br>';


            echo '<span class="menu-item-description';
            if (!$item->translatedDescription($language)) {
                echo ' empty"';
            } else {
                echo '"';
            }
            echo '>' . $item->translatedDescription($language) . '</span>';

            if (!$menus && $prices && $item->price && $item->price > 0) {
                echo '<span class="menu-item-price">';
                self::printCurrency();

                echo self::formatDecimal($item->price) . '</span>';
            }

            if ($allergies && count($item->allergies)) {
                echo '<div class="allergy-icons">';

                foreach ($item->allergies as $allergy) {

                    $iconLink = "img/menu/allergies/" . $allergy->name . ".png";
                    $iconTitle = trans('allergies.' . $allergy->name);

                    echo '<span class="allergy-icon" title="' . $iconTitle . '">';
                    echo '<img src="' . asset($iconLink) . '" alt="' . $iconTitle . '" />';
                    echo '</span>';
                }

                echo '</div>';
            }

            echo '</div>';

            if (!$menus) {
                echo '<div class="action-btn">';
                echo '<a href="javascript:;" class="action-btn-add item-action-btn-add">' . trans('online.add_button') . '</a>';
                echo '</div>';
            }

            echo '<span class="menu-item-appended"></span>';

            echo '<div class="clear"></div>';
            echo '</div>';
        }

        echo '<div class="clear"></div>';
        echo '</div><!-- .items-row -->';
    }

    public static function printCurrency()
    {
        if (\App\Config::$currency == 'EUR') {
            echo '&euro;';
        } else if (\App\Config::$currency == 'USD') {
            echo '&#36;';
        }
    }

    //color in hex (#123123)
    //opacity 2 digits integer (percent). Example: 92
    public static function getColorAndOpacityCssValue($color, $opacity)
    {

        $opacity = intval($opacity);
        $rgb = self::hex2rgb($color);
        if ($opacity < 0) {
            $opacity = 0;
        }

        if ($opacity >= 100) {
            return 'rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 1)';
        } else {
            return 'rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.' . $opacity . ')';
        }
    }

    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {

            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {

            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function cleanFontName($fontName)
    {
        $fontName = str_replace('+', ' ', $fontName);
        return $fontName;
    }

    public static function formatDecimal($num, $decimals = 2)
    {
        if (!$num || $num == 0) {
            return null;
        }

        if (TenantConfig::$decimal_point == '.') {
            $decimalPoint = '.';
            $thousandsPoint = ',';
        } else {
            $decimalPoint = ',';
            $thousandsPoint = '.';
        }

        return number_format($num, $decimals, $decimalPoint, $thousandsPoint);
    }

    public static function getMenuAlbumCategories()
    {

        $photoCategoriesFull = File::directories(public_path('img/menu/backgrounds'));
        $photoCategories = [];
        foreach ($photoCategoriesFull as $path) {
            $thisPath = explode('/menu/backgrounds/', $path);
            if ($thisPath && is_array($thisPath) && count($thisPath) == 2) {
                $photoCategories[] = $thisPath[1];
            }
        }
        $photoCategories[] = 'uploads';

        return $photoCategories;
    }

    public static function getResizedAlbumImage($url, $size)
    {
        $lastSlashPos = strrpos($url, '/');
        return substr($url, 0, $lastSlashPos) . '/' . $size . substr($url, $lastSlashPos);
    }

    public static function importGoogleFonts($config)
    {

        $usedFonts = [];

        $fontKeys = [
            'content_font',
            'main_category_font',
            'sub_category_font',
            'content_font',
            'item_name_font',
            'item_description_font',
            'item_price_font',
            'navigation_font',
        ];

        foreach ($config as $key => $value) {

            if (in_array($key, $fontKeys) && !in_array($value, $usedFonts)) {
                $usedFonts[] = $value;
                echo '<link href="https://fonts.googleapis.com/css?family=' . $value . ':400,700" data-font-name="' . $value . '" rel="stylesheet">';
            }
        }
    }

    public static function printAlignmentControls($value)
    {

        $availableAlignments = [
            'left' => 'align_left',
            'center' => 'align_center',
            'right' => 'align_right'
        ];

        $html = '';
        foreach ($availableAlignments as $alignment => $key) {
            $html .= '<a href="javascript:;" class="control alignment-control';
            if ($alignment == $value) {
                $html .= ' active';
            }
            $html .= '" data-alignment="' . $alignment . '" title="' . trans('menu.' . $key) . '">';
            $html .= '<img src="' . asset('img/menu/controls/' . $key . '.png') . '" alt="' . trans('menu.' . $key) . '" />';
            $html .= '</a>';
        }

        return $html;
    }

    public static function printFormattingControls($values)
    {

        $html = '';
        foreach ($values as $key => $value) {
            $html .= '<a href="javascript:;" class="control format-control';
            if ($value) {
                $html .= ' active';
            }
            $html .= '" data-format="' . $key . '" title="' . trans('menu.' . $key) . '">';
            $html .= '<img src="' . asset('img/menu/controls/' . $key . '.png') . '" alt="' . trans('menu.' . $key) . '" />';
            $html .= '</a>';
        }

        return $html;
    }

    public static function getLanguages()
    {

        $arr = [];

        foreach (self::$languages as $language) {
            $arr[$language] = trans('languages.' . $language);
        }

        return $arr;
    }

    public static function getMenuTranslationLanguages()
    {

        /*
        $languages = \App\MenuLanguage::all();
        $defaultLanguage = new \App\MenuLanguage([
            'language'	=>	'',
            'published'	=>	true,
        ]);

        array_unshift($languages, $defaultLanguage);

        return $languages;
        */
    }

    public static function setPreordersMenuLanguage($val)
    {

    }

    public static function getPreordersMenuLanguage()
    {

        $languages = MenuLanguage::all();
        $language = Session::has('filters.preorders.language') ? Session::get('filters.preorders.language') : MenuLanguage::getDefaultLanguage();

        return $language;
    }

    public static function getPreordersSummary($preorders)
    {

        $language = self::getPreordersMenuLanguage();

        $itemsArray = [];
        $quantitiesArray = [];
        foreach ($preorders as $preorder) {
            foreach ($preorder->items as $item) {
                if (array_key_exists($item->id, $itemsArray)) {
                    $quantitiesArray[$item->id] += $item->pivot->quantity;
                } else {
                    $itemsArray[$item->id] = $item;
                    $quantitiesArray[$item->id] = $item->pivot->quantity;
                }
            }

            foreach ($preorder->groups as $group) {

                if ($group->pivot->items) {
                    $items = \App\MenuItem::findMany(array_map('intval', explode(',', $group->pivot->items)));
                    foreach ($items as $item) {
                        if (array_key_exists($item->id, $itemsArray)) {
                            $quantitiesArray[$item->id] += $group->pivot->quantity;
                        } else {
                            $itemsArray[$item->id] = $item;
                            $quantitiesArray[$item->id] = $group->pivot->quantity;
                        }
                    }
                }
            }
        }

        $resultStr = '';
        $first = true;
        foreach ($itemsArray as $itemId => $item) {
            if (!$first) {
                $resultStr .= '<hr>';
            }
            $first = false;

            $resultStr .= $quantitiesArray[$itemId] . ' x ' . $item->translatedName($language) . '<br>';
        }

        return $resultStr;
    }
}