<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'database'], function () {

    /** Online menu routes **/
    Route::get('/online-menu', 'OnlineMenuController@index')->middleware('menu.url');

    Route::get('/set-menu-language/{lang?}', 'Online\OnlineController@setMenuLanguage');
    Route::get('/terms', 'Online\OnlineController@terms');

    Route::group(['middleware' => 'online.language'], function () {

        Route::get('/set-language/{lang?}', 'Online\OnlineController@setLanguage');


        /** Menu Cart Routes **/
        Route::group(['prefix' => 'cart'], function () {

            Route::get('/add/items/{id}', 'Online\MenuCartController@addItem');
            Route::get('/deduct/items/{id}', 'Online\MenuCartController@deductItem');
            Route::get('/remove/items/{id}', 'Online\MenuCartController@removeItem');

            Route::post('/add/groups/{id}', 'Online\MenuCartController@addGroup');
            Route::get('/increment/groups/{key}', 'Online\MenuCartController@incrementGroup');
            Route::get('/deduct/groups/{key}', 'Online\MenuCartController@deductGroup');
            Route::get('/remove/groups/{key}', 'Online\MenuCartController@removeGroup');

            Route::get('/get', 'Online\MenuCartController@getCart');
            Route::get('/clear', 'Online\MenuCartController@clearCart');
        });


        Route::group(['prefix' => 'preorder', 'middleware' => 'features.preorders'], function () {

            Route::post('/new', 'Online\PreordersController@create');
            Route::post('/set-id', 'Online\PreordersController@setId');
            Route::post('/delete', 'Online\PreordersController@delete');
            Route::delete('/destroy', 'Online\PreordersController@destroy');
            Route::get('/menu', 'Online\PreordersController@menu');
            Route::post('/submit', 'Online\PreordersController@submit');
            Route::get('/confirm', 'Online\PreordersController@confirm');
            Route::post('/confirmed', 'Online\PreordersController@postConfirm');
            Route::get('/success', 'Online\PreordersController@success');

            Route::post('/delete', 'Online\PreordersController@delete');

            //error pages
            Route::get('/error/{errorType?}', 'Online\PreordersController@errorPage')->name('preorder.error');

            Route::get('/{identifier}/{token}', 'Online\PreordersController@index');
        });

        Route::group(['prefix' => 'online-reservation'], function () {

            Route::get('/', 'Online\ReservationsController@step1');
            Route::post('/', 'Online\ReservationsController@postStep1');

            Route::get('/step2', 'Online\ReservationsController@step2');
            Route::post('/step2', 'Online\ReservationsController@postStep2');

            Route::get('/step3', 'Online\ReservationsController@step3');
            Route::post('/step3', 'Online\ReservationsController@postStep3');

            Route::get('/success', 'Online\ReservationsController@completionPage');

            Route::get('/get-times/{date}/{shift}/{personsNum?}/{online?}', 'Online\ReservationsController@getTimesForDate');

            Route::get('/terms', 'Online\ReservationsController@terms');
            Route::get('/duplicate', 'Online\ReservationsController@duplicate');

            Route::get('/cancel-reservation/{reference_id}/{client_token}', 'Online\ReservationsController@cancelReservation');
            Route::post('/cancel-reservation', 'Online\ReservationsController@postCancelReservation');

            Route::get('/clear-session', 'Online\ReservationsController@clearSession');

        });
    });

    Route::group(['middleware' => 'csrf'], function () {

        Route::group(['middleware' => 'auth.manager', 'prefix' => 'manager'], function () {

            Route::get('/', function () {
                return redirect()->action('TenantsController@index');
            });

            Route::get('/tenants', 'TenantsController@index');

            Route::get('tenants/migrate', 'TenantsController@migrateTenants');

            Route::get('tenants/{id}/users/{userId}/edit', 'TenantsController@editTenantUser');
            Route::post('tenants/{id}/users/{userId}/update', 'TenantsController@updateTenantUser');
            Route::post('tenants/{id}/users/{userId}/reset-password', 'TenantsController@resetUserPassword');
            Route::get('tenants/{id}/users/create', 'TenantsController@createTenantUser');
            Route::post('tenants/{id}/users/store', 'TenantsController@storeTenantUser');

            Route::delete('tenants/{id}/users/{userId?}', 'TenantsController@destroyTenantUser');

            Route::post('tenants/{id}/config/update', 'TenantsController@updateTenantConfig');
            Route::post('tenants/{id}/interfaces/update', 'TenantsController@updateInterfaces');

            Route::get('tenants/{id}/enable-pin', 'TenantsController@enablePin');
            Route::get('tenants/{id}/disable-pin', 'TenantsController@disablePin');
            Route::get('tenants/{id}/reset-pin', 'TenantsController@resetPin');

            Route::get('/tenants/create', 'TenantsController@create');
            Route::post('/tenants/store', 'TenantsController@store');

            Route::get('/tenants/{id}/{tab?}', 'TenantsController@show');
        });

        Route::get('/home', function () {
            return redirect('/');
        });

        Route::group(['middleware' => ['auth']], function () {

            Route::get('/', function () {
                return redirect('reception');
            });

            Route::get('/contact', function () {
                return view('pages.contact')->with([
                    'title' => trans('general.contact_us')
                ]);
            });

            //Route::resource('users', 'UsersController');
        });

        Route::group(['middleware' => ['auth'], 'prefix' => 'reception'], function () {

            Route::get('/', function () {
                return redirect()->action('ReservationsController@index');
            });

            Route::get('reservations/status_config', 'ConfigController@currentReservationStatus');
            Route::get('reservations/print', 'ReservationsController@printReservations');

            Route::post('reservations/filter', 'ReservationsController@filter');
            Route::resource('reservations/statuses', 'ReservationStatusesController');

            Route::get('reservations/create/{date?}', 'ReservationsController@create');

            Route::resource('reservations', 'ReservationsController', ['except' => ['create']]);

            Route::get('reservations/{id}/serve-offer', 'ReservationsController@serveOffer');

            //Ajax Reservation Requests
            Route::get('ajax/reservations/mark-showed/{id}', 'ReservationsController@markShowed');
            Route::get('ajax/reservations/unmark-showed/{id}', 'ReservationsController@unmarkShowed');

            Route::get('ajax/reservations/mark-left/{id}', 'ReservationsController@markLeft');
            Route::get('ajax/reservations/unmark-left/{id}', 'ReservationsController@unmarkLeft');

            Route::post('ajax/reservations/walkin', 'ReservationsController@postWalkin');

            Route::get('ajax/reservations/get-records/{date}/{timeOfDay}/{last?}', 'ReservationsController@getRecords');

            Route::get('ajax/reservations/set-status/{id}', 'ReservationsController@setStatus');

            Route::get('ajax/reservations/delete/{id}', 'ReservationsController@destroy');

            Route::get('ajax/reservations/update-table/{id}', 'ReservationsController@updateTable');

            Route::post('ajax/reservations/update-lights', 'ReservationsController@updateLights');

            Route::get('ajax/get-times/{date}/{shift}', 'ReservationsController@getAvailableTimesForDate');

            Route::get('ajax/table-plan-objects/{date}/{shift}', 'ReservationsController@getTablePlanObjectsOnDate');
        });

        Route::group(['middleware' => ['auth'], 'prefix' => 'kitchen'], function () {

            Route::get('/', function () {
                return redirect()->action('PreordersController@index');
            });

            Route::get('preorders', 'PreordersController@index');
            Route::get('preorders/{reservationId}/show', 'PreordersController@show');

            Route::get('preorders/{reservationId}/menu', 'PreordersController@menu');
        });

        Route::group(['middleware' => ['auth'], 'prefix' => 'restaurant'], function () {

            Route::get('/', function () {
                return redirect()->action('SectionsController@index');
            });

            //menu
            Route::get('menu/set-language/{lang}', 'MenuItemsController@setLanguage');

            Route::get('menu/items/sort/{categoryId?}', 'MenuItemsController@getSort');
            Route::get('ajax/menu/items/order', 'MenuItemsController@order');
            Route::get('menu/reset-settings', 'OnlineMenuController@resetSettings');

            Route::resource('menu/items', 'MenuItemsController');
            Route::get('menu/items/create/{categoryId?}', 'MenuItemsController@create');
            Route::post('menu/items/filter', 'MenuItemsController@filter');
            Route::post('menu/items/upload', 'MenuItemsController@uploadItemImage');
            Route::post('menu/items/crop', 'MenuItemsController@cropItemImage');

            Route::post('/menu/items/multiple', 'MenuItemsController@multipleItemsAction');

            Route::resource('menu/categories', 'MenuCategoriesController');
            Route::post('menu/categories/upload', 'MenuCategoriesController@uploadCategoryImage');
            Route::post('menu/categories/crop', 'MenuCategoriesController@cropCategoryImage');

            Route::resource('menu/groups', 'MenuGroupsController');
            Route::get('ajax/menu/groups/order', 'MenuGroupsController@order');

            Route::resource('custom-menus', 'CustomMenusController');
            Route::get('ajax/custom-menus/order', 'CustomMenusController@order');

            Route::post('menu/groups/set-background', 'OnlineMenuController@setMenusBackgroundImage');

            Route::get('ajax/menu/categories/sort', 'MenuCategoriesController@sort');

            Route::get('menu/editor', 'OnlineMenuController@editor');

            Route::get('ajax/menu/set-theme/{themeId}', 'OnlineMenuController@setTheme');

            Route::get('menu/photo-categories/{category}/photos', 'OnlineMenuController@getCategoryPhotos');

            Route::get('menu/temp', 'OnlineMenuController@temp');
            Route::post('menu/submit-temp', 'OnlineMenuController@submitTemp');
            Route::get('menu/save', 'OnlineMenuController@saveChanges');

            Route::post('menu/set-title-translations', 'OnlineMenuController@setMenuTitleTranslations');
            Route::post('ajax/menu/upload-background-photo', 'OnlineMenuController@uploadBackgroundPhoto');
            Route::post('ajax/menu/delete-uplaoded-photo', 'OnlineMenuController@deleteUploadedPhoto');

            //sections
            Route::resource('sections', 'SectionsController');
            Route::post('ajax/sections/order', 'SectionsController@order');

            // table plan schedule
            Route::get('table-plan-schedule', 'TablePlansController@tablePlanSchedule');
            Route::post('table-plan-schedule/update', 'TablePlansController@updateTablePlanSchedule');
            Route::get('table-plan-records/{id}/remove', 'TablePlansController@removeTablePlanRecord');
            Route::post('table-plan-records/create', 'TablePlansController@createTablePlanRecord');

            // table plans
            Route::resource('table-plans', 'TablePlansController');
            Route::post('ajax/table-plans/order', 'TablePlansController@order');
        });

        Route::group(['middleware' => ['auth'], 'prefix' => 'crm'], function () {

            Route::get('/', function () {
                return redirect()->action('ClientsController@index');
            });

            Route::post('clients/filter', 'ClientsController@filter');
            Route::get('clients/clear-filters', 'ClientsController@clearFilters');

            Route::post('companies/filter', 'CompaniesController@filter');
            Route::get('companies/clear-filters', 'CompaniesController@clearFilters');

            Route::resource('clients', 'ClientsController');
            Route::resource('companies', 'CompaniesController');

            Route::get('ajax/client/autocomplete/last-name/{string?}', 'ClientsController@autocompleteLastName');
            Route::get('ajax/client/autocomplete/name/{string?}', 'ClientsController@autocompleteName');
            Route::get('ajax/client/autocomplete/phone/{string?}', 'ClientsController@autocompletePhone');
            Route::get('ajax/client/autocomplete/mobile/{string?}', 'ClientsController@autocompleteMobile');
            Route::get('ajax/company/autocomplete/name/{string?}', 'CompaniesController@autocompleteName');
        });


        Route::group(['middleware' => ['auth', 'auth.pin'], 'prefix' => 'admin'], function () {

            Route::get('/', function () {
                return redirect()->action('ConfigController@basic');
            });

            Route::resource('users', 'UsersController');
            Route::resource('offdays', 'OffdaysController');

            Route::get('config/basic', 'ConfigController@basic');
            Route::post('config/basic', 'ConfigController@postBasic');


            Route::get('config/client-status', 'ConfigController@clientStatus');
            Route::post('config/client-status', 'ConfigController@postClientStatus');

            Route::get('config/reservation-hours', 'ConfigController@reservationHours');
            Route::post('config/reservation-hours', 'ConfigController@postReservationHours');

            Route::get('config/online', 'ConfigController@online');
            Route::post('config/online', 'ConfigController@postOnline');

            Route::get('config/online-languages', 'ConfigController@onlineLanguages');
            Route::post('config/online-languages', 'ConfigController@postOnlineLanguages');

            Route::get('config/preorders', 'ConfigController@preorders');
            Route::post('config/preorders', 'ConfigController@postPreorders');

            Route::get('change-pass', 'UsersController@editPassword');
            Route::post('change-pass', 'UsersController@updatePassword');
        });

        Route::group(['middleware' => ['auth', 'auth.pin'], 'prefix' => 'analytics'], function () {

            Route::get('/', function () {
                return redirect()->action('AnalyticsController@reservationsDailyStats');
            });

            Route::get('/reservations/daily-stats', 'AnalyticsController@reservationsDailyStats');
            Route::get('/reservations/monthly-stats', 'AnalyticsController@reservationsMonthlyStats');
            Route::get('/reservations/statuses', 'AnalyticsController@reservationStatuses');
            Route::get('/reservations/source-report', 'AnalyticsController@reservationsRefReport');

            Route::post('/reservations/set-daily-report-date-range', 'AnalyticsController@setReservationsDailyReportDateRange');
            Route::post('/reservations/set-reservations-monthly-period', 'AnalyticsController@setReservationsMonthlyPeriod');
            Route::post('/reservations/set-statuses-monthly-period', 'AnalyticsController@setStatusesMonthlyPeriod');
            Route::post('/reservations/set-source-report-period', 'AnalyticsController@setReservationsSourceReportPeriod');

            Route::get('/ajax/reservations/daily-data/{from?}/{to?}', 'AnalyticsController@reservationsDailyStatsData');
            Route::get('/ajax/reservations/monthly-data/{months?}', 'AnalyticsController@reservationsMonthlyStatsData');
            Route::get('/ajax/reservations/statuses-data/{months?}', 'AnalyticsController@reservationStatusesData');
            Route::get('/ajax/reservations/source-data/{from?}/{to?}', 'AnalyticsController@reservationsSourceData');
        });

        Route::group(['middleware' => 'auth'], function () {
            Route::get('auth/pin', 'Auth\PinController@getLogin');
            Route::post('auth/pin', 'Auth\PinController@postLogin');
        });

        Route::get('auth/login', 'Auth\LoginController@showLoginForm');
        Route::post('auth/login', 'Auth\LoginController@login');
        Route::post('auth/logout', 'Auth\LoginController@logout');

    });
});
