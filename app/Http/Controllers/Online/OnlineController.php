<?php namespace App\Http\Controllers\Online;

use App\Config as TenantConfig;
use App\MenuLanguage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnlineController extends Controller
{
    public function terms()
    {
        return view('online.general.terms')->with([
            'title' => 'VITisch - ' . trans('general.terms_and_conditions')
        ]);
    }

    function setLanguage($lang)
    {

        $availableLanguages = TenantConfig::$availableLanguages;

        if (array_key_exists($lang, $availableLanguages)) {
            session()->put('online.language', $lang);
        }

        return redirect()->back();
    }

    function setMenuLanguage($lang)
    {

        $availableLanguages = MenuLanguage::published()->get();

        //$availableLanguages = \App\Misc::getMenuTranslationLanguages();
        //array_unshift($availableLanguages, TenantConfig::$language);
        foreach ($availableLanguages as $language) {
            if ($lang == $language->language) {
                session()->put('menu.language', $lang);
            }
        }

        return redirect()->back();
    }
}
