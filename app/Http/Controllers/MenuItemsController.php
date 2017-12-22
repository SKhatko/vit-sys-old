<?php namespace App\Http\Controllers;

use App\Tree;
use App\MenuItem;
use App\MenuCategory;
use App\Allergy;
use App\MenuLanguage;
use App\MenuItemTranslation;

use App\Config as TenantConfig;
use App\Http\Requests;
use App\Http\Requests\MenuItemRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Misc;
use Session;
use DB;

class MenuItemsController extends Controller
{

    private static $tmpUploadPath;
    private static $imgUploadPath;
    private static $croppedUploadPath;
    private static $thumbUploadPath;

    public function __construct()
    {
        $subDomain = TenantConfig::$sub_domain;
        self::$tmpUploadPath = 'uploads/menu-items/' . $subDomain . '/temp/';
        self::$imgUploadPath = 'uploads/menu-items/' . $subDomain . '/';
        self::$croppedUploadPath = 'uploads/menu-items/' . $subDomain . '/cropped/';
        self::$thumbUploadPath = 'uploads/menu-items/' . $subDomain . '/thumb/';
    }


    public function setLanguage($lang)
    {

        $availableLanguages = MenuLanguage::all();

        //$availableLanguages = \App\Misc::getMenuTranslationLanguages();
        //array_unshift($availableLanguages, TenantConfig::$language);
        foreach ($availableLanguages as $language) {
            if ($lang == $language->language) {
                session()->put('filters.menu.language', $lang);
            }
        }

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('restaurant.restaurant_menu');
        $pageName = 'menu-items';

        if (session()->has('filters.menu.category_id')) {
            $filterCategoryId = session()->get('filters.menu.category_id');
            $items = MenuItem::where('category_id', '=', $filterCategoryId)->ordered()->get();
        } else {
            $filterCategoryId = NULL;
            $items = MenuItem::ordered()->get();
        }

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $language = Session::has('filters.menu.language') ? Session::get('filters.menu.language') : MenuLanguage::getDefaultLanguage();

        return view('restaurant.menu_items.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'items' => $items,
            'categoriesTree' => $categoriesTree,
            'filterCategoryId' => $filterCategoryId,
            'language' => $language
        ]);
    }


    public function getSort($categoryId = NULL)
    {

        $title = trans('restaurant.change_items_order');
        $pageName = 'items-order';

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $language = MenuLanguage::getDefaultLanguage();

        $menuItems = NULL;

        if ($categoryId) {
            $category = MenuCategory::findOrFail($categoryId);
            $menuItems = $category->menu_items;
        }

        return view('restaurant.menu_items.sort')->with([
            'title' => $title,
            'pageName' => $pageName,
            'categoryId' => $categoryId,
            'categoriesTree' => $categoriesTree,
            'menuItems' => $menuItems,
            'language' => $language,
        ]);
    }

    public function order(Request $request)
    {
        $items = $request->all();
        $counter = 0;

        foreach ($items as $itemName => $id) {
            $item = MenuItem::findOrFail($id[0]);
            $item->order_num = $counter++;
            $item->save();
        }
    }

    public function filter(Request $request)
    {

        if ($request->input('category_id')) {
            $category = MenuCategory::find($request->input('category_id'));
            if ($category) {
                session()->put('filters.menu.category_id', $category->id);
            } else {
                session()->forget('filters.menu.category_id');
            }
        } else {
            session()->forget('filters.menu.category_id');
        }

        return redirect()->action('MenuItemsController@index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($filterCategoryId = null)
    {
        $title = trans('restaurant.new_menu_item');
        $pageName = 'menu-items';

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $filterCategoryId = $filterCategoryId ? $filterCategoryId : NULL;

        $foodAllergies = Allergy::food()->get();
        $drinkAllergies = Allergy::drinks()->get();

        $menuLanguages = MenuLanguage::all();
        $language = \App\Config::$language;

        return view('restaurant.menu_items.create')->with([
            'title' => $title,
            'pageName' => $pageName,
            'categoriesTree' => $categoriesTree,

            'filterCategoryId' => $filterCategoryId,

            'foodAllergies' => $foodAllergies,
            'drinkAllergies' => $drinkAllergies,

            'menuLanguages' => $menuLanguages,
            'language' => $language,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MenuItemRequest $request)
    {
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

        $item = MenuItem::create($request->all());

        if ($request->input('allergies') && count($request->input('allergies'))) {
            $item->allergies()->attach($request->input('allergies'));
        }

        //handle translations
        $this->insertTranslations($item->id, $request->input('names'), $request->input('descriptions'));

        session()->flash('flash_message', trans('restaurant.store_item_success_msg', ['name' => $item->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuItemsController@index');
    }

    private function insertTranslations($itemId, $names, $descriptions)
    {
        $menuTranslationLanguages = MenuLanguage::all();

        //$menuTranslationLanguages = Misc::getMenuTranslationLanguages();

        if ($names && is_array($names) && count($names)) {

            foreach ($menuTranslationLanguages as $language) {
                $lang = $language->language;
                if (isset($names[$lang])) {
                    $thisDescription = (isset($descriptions[$lang]) && !empty($descriptions[$lang])) ? $descriptions[$lang] : NULL;
                    MenuItemTranslation::create([
                        'item_id' => $itemId,
                        'language' => $lang,
                        'name' => $names[$lang],
                        'description' => $thisDescription
                    ]);
                }
            }
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
        $title = trans('restaurant.edit_menu_item');
        $pageName = 'menu-items';

        $item = MenuItem::findOrFail($id);

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $filterCategoryId = NULL;

        $foodAllergies = Allergy::food()->get();
        $drinkAllergies = Allergy::drinks()->get();

        //$menuTranslationLanguages = Misc::getMenuTranslationLanguages();
        $menuLanguages = MenuLanguage::all();
        $language = \App\Config::$language;

        return view('restaurant.menu_items.edit')->with([
            'title' => $title,
            'pageName' => $pageName,
            'item' => $item,
            'categoriesTree' => $categoriesTree,

            'filterCategoryId' => $filterCategoryId,

            'foodAllergies' => $foodAllergies,
            'drinkAllergies' => $drinkAllergies,

            //'menuTranslationLanguages' =>	$menuTranslationLanguages,
            'menuLanguages' => $menuLanguages,
            'language' => $language
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(MenuItemRequest $request, $id)
    {
        $item = MenuItem::findOrFail($id);

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

        $item->update($request->all());

        if ($request->input('allergies')) {
            $item->allergies()->sync($request->input('allergies'));
        } else {
            $item->allergies()->sync([]);
        }


        /*** handle translations ***/

        $menuTranslationLanguages = MenuLanguage::all();

        //delete translations for active languages only. Inactive languages we want to keep intact
        $query = MenuItemTranslation::where('item_id', '=', $id)->where(function ($query) use ($menuTranslationLanguages) {
            $query->whereNull('name'); //always false (only for chaining ors)
            foreach ($menuTranslationLanguages as $language) {
                $lang = $language->language;
                $query->orWhere('language', '=', $lang);
            }
        })->delete();

        $this->insertTranslations($id, $request->input('names'), $request->input('descriptions'));

        /***************************/


        session()->flash('flash_message', trans('restaurant.update_item_success_msg', ['name' => $item->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuItemsController@index');
    }


    public function uploadItemImage(Request $request)
    {

        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
        $temp = explode(".", $_FILES["img"]["name"]);
        $extension = end($temp);

        if (in_array($extension, $allowedExts)) {
            if ($_FILES["img"]["error"] > 0) {
                $response = array(
                    "status" => 'error',
                    "message" => 'ERROR Return Code: ' . $_FILES["img"]["error"],
                );
            } else {

                $filename = $_FILES["img"]["tmp_name"];
                list($width, $height) = getimagesize($filename);

                //move_uploaded_file($filename,  public_path(self::$tmpUploadPath) . $_FILES["img"]["name"]);
                $fileName = time() . '_' . $_FILES["img"]["name"];
                $request->file('img')->move(public_path(self::$tmpUploadPath), $fileName);

                $response = array(
                    "status" => 'success',
                    "url" => '/' . self::$tmpUploadPath . $fileName,
                    "width" => $width,
                    "height" => $height
                );

            }
        } else {
            $response = array(
                "status" => 'error',
                "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
            );


        }

        return $response;

    }

    public function cropItemImage(Request $request)
    {

        $imgUrl = public_path($_POST['imgUrl']);
        // original sizes
        $imgInitW = $_POST['imgInitW'];
        $imgInitH = $_POST['imgInitH'];
        // resized sizes
        $imgW = $_POST['imgW'];
        $imgH = $_POST['imgH'];
        // offsets
        $imgY1 = $_POST['imgY1'];
        $imgX1 = $_POST['imgX1'];
        // crop box
        $cropW = $_POST['cropW'];
        $cropH = $_POST['cropH'];
        // rotation angle
        $angle = $_POST['rotation'];

        $jpeg_quality = 100;

        //check if directory exists
        if (!file_exists(public_path(self::$croppedUploadPath))) {
            mkdir(public_path(self::$croppedUploadPath), 0755, true);
        }

        $fileName = "mi_" . rand() . rand() . rand() . '_' . time();
        $output_filename = public_path(self::$croppedUploadPath) . $fileName;

        $what = getimagesize($imgUrl);

        switch (strtolower($what['mime'])) {
            case 'image/png':
                $img_r = imagecreatefrompng($imgUrl);
                $source_image = imagecreatefrompng($imgUrl);
                $type = '.png';
                break;
            case 'image/jpeg':
                $img_r = imagecreatefromjpeg($imgUrl);
                $source_image = imagecreatefromjpeg($imgUrl);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                $img_r = imagecreatefromgif($imgUrl);
                $source_image = imagecreatefromgif($imgUrl);
                $type = '.gif';
                break;
            default:
                die('image type not supported');
        }


        //Classic PHP code...
        // resize the original image to size of editor
        $resizedImage = imagecreatetruecolor($imgW, $imgH);
        imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
        // rotate the rezized image
        $rotated_image = imagerotate($resizedImage, -$angle, 0);
        // find new width & height of rotated image
        $rotated_width = imagesx($rotated_image);
        $rotated_height = imagesy($rotated_image);
        // diff between rotated & original sizes
        $dx = $rotated_width - $imgW;
        $dy = $rotated_height - $imgH;
        // crop rotated image to fit into original rezized rectangle
        $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
        imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
        imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
        // crop image into selected area
        $final_image = imagecreatetruecolor($cropW, $cropH);
        imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
        imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
        // finally output png image
        //imagepng($final_image, $output_filename.$type, $png_quality);

        imagejpeg($final_image, $output_filename . $type, $jpeg_quality);


        //create thumbnail as well
        /*
        $thumbWidth = 200;
        $thumbHeight = 150;

        $fileName = $fileName.$type;

        ImageResizer::createImage($fileName,
                   $output_filename.$type,
                   public_path(self::$thumbUploadPath),
                   public_path(self::$thumbUploadPath).$fileName,
                   $thumbWidth,
                   $thumbHeight);
        */

        $response = Array(
            "status" => 'success',
            "url" => str_replace(public_path(), '', $output_filename) . $type
        );

        print json_encode($response);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);

        $itemName = $item->name;

        $item->allergies()->detach();
        $item->translations()->delete();
        $item->delete();

        session()->flash('flash_message', trans('restaurant.delete_item_success_msg', ['name' => $itemName]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuItemsController@index');
    }

    public function multipleItemsAction(Request $request)
    {

        $action = $request->input('action');
        $items = $request->input('item_selection');

        if ($items && is_array($items) && count($items)) {
            if ($action == 'delete') {
                return $this->deleteMultipleItems($request->input('item_selection'));
            } else if ($action == 'move') {
                return $this->moveMultipleItems($request->input('item_selection'), $request->input('category_id'));
            } else {
                return 'Error handling request';
            }
        } else {
            return redirect()->back();
        }
    }

    private function deleteMultipleItems($items)
    {

        $itemsCount = count($items);

        MenuItem::whereIn('id', $items)->delete();
        MenuItemTranslation::whereIn('item_id', $items)->delete();
        DB::connection('tenant')->table('allergy_menu_item')->whereIn('menu_item_id', $items)->delete();

        //redirect back
        session()->flash('flash_message', trans('restaurant.delete_items_success_msg_with_number', ['count' => $itemsCount]));
        session()->flash('flash_message_type', 'alert-info');

        return redirect()->action('MenuItemsController@index');
    }

    private function moveMultipleItems($items, $categoryId)
    {

        $itemsCount = count($items);

        $category = MenuCategory::findOrFail($categoryId);

        MenuItem::whereIn('id', $items)->update([
            'category_id' => $categoryId
        ]);

        //redirect back
        session()->flash('flash_message', trans('restaurant.move_items_success_msg_with_number', ['count' => $itemsCount]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuItemsController@index');
    }
}
