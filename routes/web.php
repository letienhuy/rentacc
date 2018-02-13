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

Route::get('/', 'HomeController@index')->name('app.home');
Route::get('/huong-dan-thue-acc', 'HomeController@howToRent')->name('app.howToRent');
Route::get('/login', 'LoginController@index')->name('app.login');
Route::get('/logout', function(){
    Auth::logout();
    return redirect()->route('app.home');
})->name('app.logout');
Route::get('/login/done', 'LoginController@done')->name('app.login.done');
Route::get('/update-account', 'UserController@updateTimeRent');
Route::group(['prefix' => 'game'], function () {
    Route::post('account-list', 'HomeController@accountList');
});
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('history', 'UserController@history')->name('user.history');
    Route::get('history/recharge', 'UserController@historyRecharge')->name('user.history.recharge');
    Route::any('recharge', 'UserController@recharge')->name('user.recharge.ajax');
    Route::post('rent-account', 'UserController@rentAccount')->name('user.rent');
    Route::middleware('shop')->prefix('shop')->group(function () {
        Route::any('/', 'UserController@shop')->name('user.shop');
        Route::any('refund', 'UserController@refund')->name('user.shop.refund');
        Route::any('bank', 'UserController@updateBank')->name('user.shop.bank');
        Route::any('withdraw', 'UserController@withdrawHistory')->name('user.shop.withdraw');
        Route::any('list-account', 'UserController@listAccount')->name('user.shop.list');
        Route::any('add-account', 'UserController@addAccount')->name('user.shop.add');
        Route::any('edit-account/{id}', 'UserController@editAccount')->name('user.shop.edit');
        Route::any('remove-account/{id}', 'UserController@removeAccount')->name('user.shop.remove');
    });
    Route::any('create-shop', 'UserController@createShop')->name('user.shop.create');
});
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('refund', 'AdminController@refund')->name('admin.refund.ajax');
    Route::any('user/{action?}', 'AdminController@user')->name('admin.user');
    Route::any('category/{action?}', 'AdminController@category')->name('admin.category');
    Route::any('pay/{action?}', 'AdminController@pay')->name('admin.pay');
});