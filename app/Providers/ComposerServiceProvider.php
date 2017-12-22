<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Auth;
use Session;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {

        View::composer('reception.*', function ($view) {
            $genders = [
                'male' => trans('general.male'),
                'female' => trans('general.female'),
            ];
            $view->with('currUser', Auth::user())
                ->with('interface', 'reception')
                ->with('genders', $genders);
        });

        View::composer('kitchen.*', function ($view) {
            $view->with('currUser', Auth::user())->with('interface', 'kitchen');
        });

        View::composer('restaurant.*', function ($view) {
            $view->with('currUser', Auth::user())->with('interface', 'restaurant');
        });

        View::composer('admin.*', function ($view) {
            $view->with('currUser', Auth::user())->with('interface', 'admin');
        });

        View::composer('crm.*', function ($view) {
            $view->with('currUser', Auth::user())->with('interface', 'crm');
        });

        View::composer('analytics.*', function ($view) {
            $view->with('currUser', Auth::user())->with('interface', 'analytics');
        });

        View::composer('manager.*', function ($view) {
            $view->with('managerInterface', true);
        });

        View::composer('online.*', function ($view) {

            $language = Session::has('online.language') ? Session::get('online.language') : \App\Config::$language;
            $languages = \App\Config::$availableLanguages;

            $view->with('language', $language)->with('languages', $languages);
        });


    }

    /**
     * @return void
     */
    public function register()
    {

    }

}
