<?php namespace App\Http\Controllers;

use App\Misc;
use App\Tree;
use App\MenuLanguage;
use App\MenuCategory;
use App\MenuCategoryTranslation;

use App\Config as TenantConfig;
use App\Http\Requests;
use App\Http\Requests\MenuCategoryRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;

class MenuCategoriesController extends Controller
{

    private static $tmpUploadPath;
    private static $imgUploadPath;
    private static $croppedUploadPath;
    private static $thumbUploadPath;

    public function __construct()
    {
        $subDomain = TenantConfig::$sub_domain;
        self::$tmpUploadPath = 'uploads/menu-categories/' . $subDomain . '/temp/';
        self::$imgUploadPath = 'uploads/menu-categories/' . $subDomain . '/';
        self::$croppedUploadPath = 'uploads/menu-categories/' . $subDomain . '/cropped/';
        self::$thumbUploadPath = 'uploads/menu-categories/' . $subDomain . '/thumb/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('restaurant.menu_categories');
        $pageName = 'categories';

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $languages = MenuLanguage::all();
        $language = \App\Config::$language;

        return view('restaurant.menu_categories.index')->with([
            'title' => $title,
            'categories' => $categories,
            'categoriesTree' => $categoriesTree,
            'languages' => $languages,
            'language' => $language,
            'pageName' => $pageName
        ]);
    }

    public function sort(Request $request)
    {
        $sortArr = $request->all();
        $orderNum = 1;
        foreach ($sortArr['item'] as $categoryId => $parentId) {
            $category = MenuCategory::findOrFail($categoryId);
            $category->order_num = $orderNum;
            $category->parent_id = $parentId !== 'null' ? $parentId : null ;
            $category->save();
            $orderNum++;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('restaurant.new_menu_category');
        $pageName = 'categories';

        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $menuLanguages = MenuLanguage::all();
        $language = \App\Config::$language;


        return view('restaurant.menu_categories.create')->with([
            'title' => $title,
            'categoriesTree' => $categoriesTree,
            'pageName' => $pageName,
            'language' => $language,
            'menuLanguages' => $menuLanguages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(menuCategoryRequest $request)
    {
        if (!$request->input('parent_id')) {
            $request->merge(['parent_id' => NULL]);
        }
        $category = MenuCategory::create($request->all());

        //handle translations
        $this->insertTranslations($category->id, $request->input('names'));

//        return MenuCategoryTranslation::find(340)->get();
//        return 1;
        return redirect()->action('MenuCategoriesController@index');
    }

    public function insertTranslations($categoryId, $names)
    {

        $menuTranslationLanguages = MenuLanguage::all();

        if ($names && is_array($names) && count($names)) {

            foreach ($menuTranslationLanguages as $language) {
                $lang = $language->language;
                if (isset($names[$lang])) {

                    MenuCategoryTranslation::create([
                        'category_id' => $categoryId,
                        'language' => $lang,
                        'name' => $names[$lang],
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
        $category = MenuCategory::findOrFail($id);
        $pageName = 'categories';

        $title = trans('restaurant.edit_category');
        $categories = MenuCategory::ordered()->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);

        $menuLanguages = MenuLanguage::all();
        $language = \App\Config::$language;

        return view('restaurant.menu_categories.edit')->with([
            'title' => $title,
            'pageName' => $pageName,
            'category' => $category,
            'categoriesTree' => $categoriesTree,
            'language' => $language,
            'menuLanguages' => $menuLanguages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(MenuCategoryRequest $request, $id)
    {
        $category = MenuCategory::findOrFail($id);

        if (!$request->input('parent_id')) {
            $request->merge(['parent_id' => NULL]);
        }

        $category->update($request->all());


        /*** handle translations ***/
        $menuTranslationLanguages = MenuLanguage::all();

        //delete translations for active languages only. Inactive languages we want to keep intact
        $query = MenuCategoryTranslation::where('category_id', '=', $id)->where(function ($query) use ($menuTranslationLanguages) {
            $query->whereNull('name'); //always false (only for chaining ors)
            foreach ($menuTranslationLanguages as $language) {
                $lang = $language->language;
                $query->orWhere('language', '=', $lang);
            }
        })->delete();

        $this->insertTranslations($id, $request->input('names'));

        /***************************/


        session()->flash('flash_message', trans('restaurant.update_category_success_msg', ['name' => $category->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuCategoriesController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $category = MenuCategory::findOrFail($id);

        $categoryName = $category->name;
        $category->delete();
        MenuCategoryTranslation::where('category_id', '=', $id)->delete();

        session()->flash('flash_message', trans('restaurant.delete_category_success_msg', ['name' => $categoryName]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('MenuCategoriesController@index');
    }

    public function order()
    {

        if (isset($_GET['item']) && is_array($_GET['item']) && count($_GET['item'])) {
            foreach ($_GET['item'] as $orderNum => $id) {
                $category = MenuCategory::findOrFail($id);
                $category->order_num = $orderNum;
                $category->save();
            }
        }

    }

    public function uploadCategoryImage(Request $request)
    {
        // Dublicated from uploadItemImage

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

    public function cropCategoryImage(Request $request)
    {
        // Dublicated from uploadItemImage

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


}
