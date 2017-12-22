<?php namespace App\Http\Controllers;

use App\MenuSingleton;
use App\MenuTheme;
use App\MenuCategory;
use App\MenuGroup;
use App\MenuLanguage;
use App\MenuTitleTranslation;
use App\Tree;
use App\Misc;

use App\ImageResizer;

use App\Config as TenantConfig;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use File;
use Session;

class OnlineMenuController extends Controller
{

    public function editor()
    {

        $title = trans('menu.online_menu_editor');
        $pageName = "online-menu-config";

        $settings = MenuSingleton::getInstance()->getTempParams();

        $themes = MenuTheme::all();
        $activeTheme = MenuTheme::findOrFail($settings['theme_id']);

        return view('restaurant.online_menu_editor')->with([
            'title' => $title,
            'pageName' => $pageName,
            'settings' => $settings,
            'themes' => $themes,
            'activeTheme' => $activeTheme,

        ]);
    }

    public function getCategoryPhotos($category)
    {

        $result = [];

        if ($category == 'uploads') {
            $subDomain = TenantConfig::$sub_domain;;
            $images = File::files(public_path('uploads/menu-backgrounds/' . $subDomain));
            foreach ($images as $imagePath) {
                $explodedPath = explode('/public/', $imagePath);
                if ($explodedPath && is_array($explodedPath) && count($explodedPath) == 2) {
                    $result[] = $explodedPath[1];
                }
            }
        } else {
            $images = File::files(public_path('img/menu/backgrounds/' . $category));
            foreach ($images as $imagePath) {
                $explodedPath = explode('/public/', $imagePath);
                if ($explodedPath && is_array($explodedPath) && count($explodedPath) == 2) {
                    $result[] = $explodedPath[1];
                }
            }
        }
        return $result;
    }

    public function setTheme($themeId)
    {

        $theme = MenuTheme::findOrFail($themeId);

        MenuSingleton::getInstance()->setTempTheme($themeId);

        return redirect()->back();
    }

    public function resetSettings()
    {
        MenuSingleton::getInstance()->resetSettings();
        return redirect()->back();
    }

    public function temp()
    {

        $tempParams = MenuSingleton::getInstance()->getTempParams();

        $theme = MenuTheme::findOrFail($tempParams['theme_id']);

        if (!$tempParams) {
            return NULL;
        } else {
            Misc::setDataView('online');
            $categories = MenuCategory::ordered()->with(['menu_items.allergies', 'menu_items.translations', 'translations'])->get()->getDictionary();
            $categoriesTree = Tree::createFromArray($categories);
            $menuGroups = MenuGroup::online()->pluck('name', 'id')->all();
            $categories = MenuCategory::ordered()->get();

            //$languages = Misc::getMenuTranslationLanguages();
            //array_unshift($languages, TenantConfig::$language);
            $menuLanguages = MenuLanguage::published()->get();

            $menuLanguage = Session::has('menu.language') ? Session::get('menu.language') : MenuLanguage::getDefaultLanguage();

            return view('online.menu.' . $theme->name)->with([
                'settings' => $tempParams,
                'categoriesTree' => $categoriesTree,
                'categories' => $categories,
                'menuGroups' => $menuGroups,
                'temp' => true,

                'menuLanguages' => $menuLanguages,
                'menuLanguage' => $menuLanguage,
            ]);
        }
    }

    public function submitTemp(Request $request)
    {
        //validate request
        //@TODO
        if (!$request->has('url_activated')) {
            $request->request->add(['url_activated' => false]);
        }

        MenuSingleton::getInstance()->storeTempParams($request->input());
        return redirect()->back();
    }

    public function saveChanges()
    {
        MenuSingleton::getInstance()->saveChanges();

        return redirect()->back();
    }

    public function uploadBackgroundPhoto(Request $request)
    {

        if ($request->file('menu-bg-upload')) {
            $subDomain = TenantConfig::$sub_domain;
            $fileName = date("Y_m_d-H_i_s-") . uniqid() . '-bg.' . $request->file('menu-bg-upload')->getClientOriginalExtension();
            $request->file('menu-bg-upload')->move(public_path() . '/uploads/menu-backgrounds/' . $subDomain . '/', $fileName);

            //$thumbName = str_replace('.', '_resized.', $fileName);
            $thumbName = $fileName;

            $sizes = [
                'thumb' => [320, 240],
                'small' => [720, 480],
                'medium' => [1400, 1050],
                'large' => [2560, 1440]
            ];

            foreach ($sizes as $key => $size) {

                ImageResizer::createImage($fileName,
                    public_path() . '/uploads/menu-backgrounds/' . $subDomain . '/' . $fileName,
                    public_path() . '/uploads/menu-backgrounds/' . $subDomain . '/' . $key . '/',
                    public_path() . '/uploads/menu-backgrounds/' . $subDomain . '/' . $key . '/' . $thumbName,
                    $size[0],
                    $size[1]);
            }

            return ['result' => 'succes'];
        }
    }

    public function setMenusBackgroundImage(Request $request)
    {

        $this->validate($request, [
            'background_image' => 'required'
        ]);

        MenuSingleton::getInstance()->setMenusBackgroundImage($request->input('background_image'));

        return redirect()->back();

    }

    public function deleteUploadedPhoto(Request $request)
    {

        $this->validate($request, [
            'photo' => 'required'
        ]);

        $photo = $request->input('photo');
        $uploadPathPos = strpos($photo, 'uploads/menu-backgrounds/');
        if ($uploadPathPos !== false) {
            $fullPath = public_path($photo);
            if (file_exists($fullPath)) {
                File::delete($fullPath);
            }

            $subDomain = TenantConfig::$sub_domain;

            //thumb delete
            $thumbnailPath = str_replace($subDomain . '/', $subDomain . '/thumb/', $fullPath);

            if (file_exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }

            //small delete
            $thumbnailPath = str_replace($subDomain . '/', $subDomain . '/small/', $fullPath);

            if (file_exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }

            //medium
            $thumbnailPath = str_replace($subDomain . '/', $subDomain . '/medium/', $fullPath);

            if (file_exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }


            //large
            $thumbnailPath = str_replace($subDomain . '/', $subDomain . '/large/', $fullPath);

            if (file_exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }

            return ['result' => 'succes'];
        }

        return ['result' => 'error'];
    }

    private function makeThumbnail($fileName, $imagePath, $storeDirectory, $storePath, $width, $height)
    {

        list($originalW, $originalH, $type) = getimagesize($imagePath);

        $jpeg_quality = 100;

        //check if directory exists
        if (!file_exists($storeDirectory)) {
            mkdir($storeDirectory, 0755, true);
        }
        $output_filename = str_replace('.', '_thumb.', $fileName);


        if ($originalW >= $originalH) {
            $newH = $height;
            $newW = intval(($newH / $originalH) * $originalW);
        } else {
            $newW = $width;
            $newH = intval(($newW / $originalW) * $originalH);
        }

        $dest_x = intval(($width - $newW) / 2);
        $dest_y = intval(($height - $newH) / 2);

        if ($type === 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } else if ($type === 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } else if ($type === 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        } else {
            return false;
        }

        $oldImage = $imgcreatefrom($imagePath);
        $newImage = imagecreatetruecolor($width, $height);

        imagecopyresampled($newImage, $oldImage, $dest_x, $dest_y, 0, 0, $newW, $newH, $originalW, $originalH);
        $imgt($newImage, $storePath);
        return file_exists($storePath);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menu = MenuSingleton::getInstance();
        if (!$menu->theme_id) {
            return '';
        }

        $theme = MenuTheme::findOrFail($menu->theme_id);

        $params = $menu->getParams();


        Misc::setDataView('online');
        $categories = MenuCategory::ordered()->with(['menu_items.allergies', 'menu_items.translations', 'translations'])->get()->getDictionary();
        $categoriesTree = Tree::createFromArray($categories);
        $menuGroups = MenuGroup::online()->pluck('name', 'id')->all();
        $categories = MenuCategory::ordered()->get();

        //$languages = Misc::getMenuTranslationLanguages();
        //array_unshift($languages, TenantConfig::$language);
        $menuLanguages = MenuLanguage::published()->get();

        $menuLanguage = Session::has('menu.language') ? Session::get('menu.language') : MenuLanguage::getDefaultLanguage();

        \App::setLocale($menuLanguage);

        $menu->countVisit();

        return view('online.menu.' . $theme->name)->with([
            'settings' => $params,
            'categoriesTree' => $categoriesTree,
            'menuGroups' => $menuGroups,
            'categories' => $categories,
            'menuLanguages' => $menuLanguages,
            'menuLanguage' => $menuLanguage,
        ]);
    }

    public function setMenuTitleTranslations(Request $request)
    {

        MenuTitleTranslation::getQuery()->delete();
        foreach ($request->input('translations') as $key => $value) {
            MenuTitleTranslation::create([
                'language' => $key,
                'title' => $value
            ]);
        }

        session()->flash('flash_message', trans('restaurant.translations_saved_successfully'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
