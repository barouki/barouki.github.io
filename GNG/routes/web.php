<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Admin\AdminController@showLogin')->name('login');
Route::post('dologin', 'Admin\AdminController@doLogin')->name('login.submit');
Route::get('logout/{flag}', 'Admin\AdminController@logout')->name('logout');

Route::group(array('middleware' => 'checkRole'), function () {

    Route::get('dashboard', 'Admin\AdminController@showDashboard')->name('dashboard');
    Route::get('my-profile', 'Admin\AdminController@MyProfile')->name('my-profile');
    Route::post('updateAdminProfile', 'Admin\AdminController@updateAdminProfile')->name('updateAdminProfile');

    Route::prefix('user')->group(function () {
        Route::get('list', 'Admin\UserController@viewListUser')->name('user/list');
        Route::post('showUserList', 'Admin\UserController@showUserList')->name('showUserList');
        Route::get('view/{id}', 'Admin\UserController@viewUser')->name('user/view');
        Route::post('deleteUser', 'Admin\UserController@deleteUser')->name('deleteUser');
    });
    Route::get('category/product/list/{id}', 'Admin\ProductController@viewListProductByCategory')->name('category/product/list');
    Route::prefix('product')->group(function () {
        Route::get('list', 'Admin\ProductController@viewListProduct')->name('product/list');
        Route::get('add', 'Admin\ProductController@viewAddProduct')->name('product/add');
        Route::get('edit/{id}', 'Admin\ProductController@viewUpdateProduct')->name('product/edit');
        Route::get('view/{id}', 'Admin\ProductController@viewProduct')->name('product/view');
        Route::post('addUpdateProduct', 'Admin\ProductController@addEditProduct')->name('addUpdateProduct');
        Route::post('showProductList', 'Admin\ProductController@showProductList')->name('showProductList');
        Route::post('deleteProduct', 'Admin\ProductController@deleteProduct')->name('deleteProduct');
        Route::post('changeProductStock', 'Admin\ProductController@changeProductStock')->name('changeProductStock');

        Route::get('category', 'Admin\ProductController@viewListCategory')->name('product/category');
        Route::post('showCategoryList', 'Admin\ProductController@showCategoryList')->name('showCategoryList');
        Route::post('addUpdateCategory', 'Admin\ProductController@addEditCategory')->name('addUpdateCategory');
        Route::post('CheckExistCategory', 'Admin\ProductController@CheckExistCategory')->name('CheckExistCategory');
        Route::post('deleteCategory', 'Admin\ProductController@deleteCategory')->name('deleteCategory');

        Route::get('unit', 'Admin\ProductController@viewListUnit')->name('product/unit');
        Route::post('showUnitList', 'Admin\ProductController@showUnitList')->name('showUnitList');
        Route::post('addUpdateUnit', 'Admin\ProductController@addEditUnit')->name('addUpdateUnit');
        Route::post('CheckExistUnit', 'Admin\ProductController@CheckExistUnit')->name('CheckExistUnit');
        Route::post('deleteUnit', 'Admin\ProductController@deleteUnit')->name('deleteUnit');
        Route::post('deleteProductPriceUnit', 'Admin\ProductController@deleteProductPriceUnit')->name('deleteProductPriceUnit');

    });

    Route::prefix('coupon')->group(function () {
        Route::get('list', 'Admin\ProductController@viewListCoupon')->name('coupon/list');
        Route::get('add', 'Admin\ProductController@viewAddCoupon')->name('coupon/add');
        Route::get('edit/{id}', 'Admin\ProductController@viewUpdateCoupon')->name('coupon/edit');
        Route::post('addCoupon', 'Admin\ProductController@addCoupon')->name('addCoupon');
        Route::post('showCouponList', 'Admin\ProductController@showCouponList')->name('showCouponList');
        Route::post('CheckExistCoupon', 'Admin\ProductController@CheckExistCoupon')->name('CheckExistCoupon');
        Route::post('deleteCoupon', 'Admin\ProductController@deleteCoupon')->name('deleteCoupon');
        Route::post('getCouponCode', 'Admin\ProductController@getCouponCode')->name('getCouponCode');
        Route::post('getProductForCoupon', 'Admin\ProductController@getProductForCoupon')->name('getProductForCoupon');
        Route::post('CouponAssigntoProduct', 'Admin\ProductController@CouponAssigntoProduct')->name('CouponAssigntoProduct');
    });

    Route::prefix('order')->group(function () {
        Route::get('list', 'Admin\OrdersController@viewListOrder')->name('order/list');
        Route::get('edit/{id}', 'Admin\OrdersController@viewUpdateOrder')->name('order/edit');
        Route::get('view/{id}', 'Admin\OrdersController@viewOrder')->name('order/view');
        Route::post('showOrderList', 'Admin\OrdersController@showOrderList')->name('showOrderList');
        Route::post('deleteOrder', 'Admin\OrdersController@deleteOrder')->name('deleteOrder');

        Route::post('getDeliveryBoyList', 'Admin\OrdersController@getDeliveryBoyList')->name('getDeliveryBoyList');
        Route::post('assignDeliveryBoy', 'Admin\OrdersController@assignDeliveryBoy')->name('assignDeliveryBoy');

        Route::get('complaint/list', 'Admin\OrdersController@viewListComplaint')->name('complaint/list');
        Route::get('complaint/view/{id}', 'Admin\OrdersController@viewComplaint')->name('complaint/view');
        Route::post('showOpenComplaintList', 'Admin\OrdersController@showOpenComplaintList')->name('showOpenComplaintList');
        Route::post('showCloseComplaintList', 'Admin\OrdersController@showCloseComplaintList')->name('showCloseComplaintList');
        Route::post('deleteComplaint', 'Admin\OrdersController@deleteComplaint')->name('deleteComplaint');
        Route::post('changeComplaintStatus', 'Admin\OrdersController@changeComplaintStatus')->name('changeComplaintStatus');

        Route::get('reviewratings/list', 'Admin\OrdersController@viewListReviewRatings')->name('reviewratings/list');
        Route::post('showOrderReviewList', 'Admin\OrdersController@showOrderReviewList')->name('showOrderReviewList');
    });

    Route::prefix('delivery/user')->group(function () {
        Route::get('list', 'Admin\OrdersController@viewListDeliveryUsers')->name('delivery/user/list');
        Route::get('add', 'Admin\OrdersController@viewAddDeliveryUsers')->name('delivery/user/add');
        Route::get('details/{id}', 'Admin\OrdersController@viewDeliveryUsers')->name('delivery/user/details');
        Route::post('addUpdateDeliveryUsers', 'Admin\OrdersController@addEditDeliveryUsers')->name('addUpdateDeliveryUsers');
        Route::post('showDeliveryUsersList', 'Admin\OrdersController@showDeliveryUsersList')->name('showDeliveryUsersList');
        Route::post('deleteDeliveryUsers', 'Admin\OrdersController@deleteDeliveryUsers')->name('deleteDeliveryUsers');
        Route::post('CheckExistUser', 'Admin\OrdersController@CheckExistUser')->name('CheckExistUser');
        Route::post('paymentResolve', 'Admin\OrdersController@paymentResolve')->name('paymentResolve');
        Route::post('showDeliveryOrderList', 'Admin\OrdersController@showDeliveryOrderList')->name('showDeliveryOrderList');
    });

    Route::prefix('settings')->group(function () {
        Route::get('banner/list', 'Admin\SettingsController@viewListBanner')->name('banner/list');
        Route::post('showBannerList', 'Admin\SettingsController@showBannerList')->name('showBannerList');
        Route::post('addUpdateBanner', 'Admin\SettingsController@addUpdateBanner')->name('addUpdateBanner');
        Route::post('deleteBanner', 'Admin\SettingsController@deleteBanner')->name('deleteBanner');

        Route::get('faq/list', 'Admin\SettingsController@viewListFAQ')->name('faq/list');
        Route::get('add', 'Admin\SettingsController@viewAddFAQ')->name('faq/add');
        Route::get('edit/{id}', 'Admin\SettingsController@viewUpdateFAQ')->name('faq/edit');
        Route::get('view/{id}', 'Admin\SettingsController@viewFAQ')->name('faq/view');
        Route::post('showFAQList', 'Admin\SettingsController@showFAQList')->name('showFAQList');
        Route::post('addUpdateFAQ', 'Admin\SettingsController@addUpdateFAQ')->name('addUpdateFAQ');
        Route::post('deleteFAQ', 'Admin\SettingsController@deleteFAQ')->name('deleteFAQ');

        Route::get('address/list', 'Admin\SettingsController@viewListCity')->name('address/list');
        Route::post('showCityList', 'Admin\SettingsController@showCityList')->name('showCityList');
        Route::post('addUpdateCity', 'Admin\SettingsController@addUpdateCity')->name('addUpdateCity');
        Route::post('CheckExistCity', 'Admin\SettingsController@CheckExistCity')->name('CheckExistCity');
        Route::post('deleteCity', 'Admin\SettingsController@deleteCity')->name('deleteCity');

        Route::post('showAreaList', 'Admin\SettingsController@showAreaList')->name('showAreaList');
        Route::post('addUpdateArea', 'Admin\SettingsController@addUpdateArea')->name('addUpdateArea');
        Route::post('CheckExistArea', 'Admin\SettingsController@CheckExistArea')->name('CheckExistArea');
        Route::post('deleteArea', 'Admin\SettingsController@deleteArea')->name('deleteArea');

        Route::get('others', 'Admin\SettingsController@viewOtherSettigs')->name('others');
        Route::post('addUpdateShipping', 'Admin\SettingsController@addUpdateShipping')->name('addUpdateShipping');

        Route::post('sendNotification', 'Admin\SettingsController@sendNotification')->name('sendNotification');
       
        Route::get('notification/list', 'Admin\SettingsController@viewListNotification')->name('notification/list');
        Route::post('showNotificationList', 'Admin\SettingsController@showNotificationList')->name('showNotificationList');
        Route::post('UpdateNotification', 'Admin\SettingsController@UpdateNotification')->name('UpdateNotification');
        Route::post('deleteNotification', 'Admin\SettingsController@deleteNotification')->name('deleteNotification');

    });

    
});