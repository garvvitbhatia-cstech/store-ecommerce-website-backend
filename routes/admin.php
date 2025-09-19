<?php
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\DropdownsController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\CmsManagementController;
use App\Http\Controllers\GalleryController;

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

Route::get('admins/login','Auth\AdminAuthController@getLogin')->name('adminLogin');
Route::any('admins/postLogin', 'Auth\AdminAuthController@postLogin')->name('adminLoginPost');
Route::get('admins/logout', 'Auth\AdminAuthController@logout')->name('adminLogout');
 
Route::group(['prefix' => 'admins','middleware' => 'adminauth'], function(){
	// Admin Dashboard

	/*Route::resource('admins', Admins\AdminsController::class);
	Route::resource('auth', Auth\AdminAuthController::class);
	Route::resource('dropdowns', Admins\DropdownsController::class);*/
	
	Route::any('/change-password/','Auth\AdminAuthController@change_password');
	
	Route::any('/','Admins\AdminsController@index')->name('index');
	Route::get('/dashboard/','Admins\AdminsController@dashboard')->name('dashboard'); 
	Route::post('/change-profile/','Admins\AdminsController@change_profile')->name('admins.change_profile');
	
	Route::any('/category/','Admins\DropdownsController@categories')->name('dropdowns.categories');
	Route::any('/add-category/','Admins\DropdownsController@add_category');
	Route::any('/edit-category/{slug}','Admins\DropdownsController@edit_category');
	
	Route::any('/users/','Admins\UsersController@users')->name('users.users');
	Route::any('/add-user/','Admins\UsersController@add_user');
	Route::any('/edit-user/{slug}','Admins\UsersController@edit_user');
	
	Route::any('/countries/','Admins\LocationsController@countries')->name('locations.countries');
	Route::any('/add-country/','Admins\LocationsController@add_country');
	Route::any('/edit-country/{slug}','Admins\LocationsController@edit_country');
	
	Route::any('/cities/','Admins\LocationsController@cities')->name('locations.cities');
	Route::any('/add-city/','Admins\LocationsController@add_city');
	Route::any('/edit-city/{slug}','Admins\LocationsController@edit_city');
	
	Route::any('/states/','Admins\LocationsController@states')->name('locations.states');
	Route::any('/add-state/','Admins\LocationsController@add_state');
	Route::any('/edit-state/{slug}','Admins\LocationsController@edit_state');
	
	Route::any('/subscriptions/','Admins\SubscriptionsController@subscriptions')->name('subscriptions.subscription');
	Route::any('/add-subscription/','Admins\SubscriptionsController@add_subscription');
	Route::any('/edit-subscription/{slug}','Admins\SubscriptionsController@edit_subscription');
	
	Route::any('/coupon-code/','Admins\SubscriptionsController@couponCode')->name('subscriptions.coupon_code');
	Route::any('/add-coupon-code/','Admins\SubscriptionsController@addCouponCode');
	Route::any('/edit-coupon-code/{slug}','Admins\SubscriptionsController@editCouponCode');
	
	Route::any('/contacts/','Admins\DropdownsController@contacts')->name('dropdowns.contacts');
	Route::any('/view-contact/{slug}','Admins\DropdownsController@view_contact');
	
	Route::any('/inner-pages/','Admins\CmsManagementController@inner_pages')->name('cms_management.inner_pages');
	Route::any('/edit-inner-page/{slug}','Admins\CmsManagementController@edit_inner_page');
	
	Route::any('/blog-categories/','Admins\BlogsController@categories')->name('blogs.categories');
	Route::any('/add-blog-category/','Admins\BlogsController@add_category');
	Route::any('/edit-blog-category/{slug}','Admins\BlogsController@edit_category');
	
	Route::any('/blogs/','Admins\BlogsController@blogs')->name('blogs.blogs');
	Route::any('/add-blog/','Admins\BlogsController@add_blog');
	Route::any('/edit-blog/{slug}','Admins\BlogsController@edit_blog');
	
	Route::any('/products/','Admins\DropdownsController@products')->name('dropdowns.products');
	Route::any('/add-product/','Admins\DropdownsController@add_product');
	Route::any('/edit-product/{slug}','Admins\DropdownsController@edit_product');
	Route::any('/upload-product-images/','Admins\DropdownsController@upload_product_images')->name('dropdowns.upload_product_images');
	Route::any('/delete-product-image/','Admins\DropdownsController@delete_product_image')->name('dropdowns.delete_product_image');
	Route::any('/get-sub-categories/','Admins\DropdownsController@get_sub_categories')->name('blogs.get_sub_categories');
	Route::any('/get-sub-sub-categories/','Admins\DropdownsController@get_sub_sub_categories')->name('blogs.get_sub_sub_categories');
	Route::any('/import-product/','Admins\DropdownsController@import_product');
	Route::any('/import-category/','Admins\DropdownsController@import_category');
	Route::any('/delete-category-bulk/','Admins\DropdownsController@delete_category_bulk');
	Route::any('/delete-product-bulk/','Admins\DropdownsController@delete_product_bulk');
	Route::any('/export-product/','Admins\DropdownsController@export_product');
	Route::any('/update-product-bulk/','Admins\DropdownsController@update_product_bulk');
	Route::any('/search-product-varient/','Admins\DropdownsController@search_products')->name('admin.search_product_varient');
	Route::any('/apply-product-offer/','Admins\DropdownsController@apply_product_offer');
	
	Route::any('/setting/','Admins\AdminsController@setting');
	Route::any('/change-logo/','Admins\AdminsController@change_logo');
	
	Route::any('/orders/','Admins\OrdersController@orders');
	Route::any('/view-order/{slug}','Admins\OrdersController@view_order');
	
	Route::any('/tags/','Admins\CmsManagementController@tags')->name('cms_management.tags');
	Route::any('/add-tag/','Admins\CmsManagementController@add_tag');
	Route::any('/edit-tag/{slug}','Admins\CmsManagementController@edit_tag');
	
	Route::any('/testimonials/','Admins\CmsManagementController@testimonials')->name('cms_management.testimonials');
	Route::any('/add-testimonial/','Admins\CmsManagementController@addTestimonial');
	Route::any('/edit-testimonial/{slug}','Admins\CmsManagementController@editTestimonial');
	
	Route::any('/brands/','Admins\CmsManagementController@brands')->name('cms_management.brands');
	Route::any('/add-brand/','Admins\CmsManagementController@addBrand');
	Route::any('/edit-brand/{slug}','Admins\CmsManagementController@editBrand');
	
	Route::any('/colors/','Admins\DropdownsController@colors')->name('dropdowns.colors');
	Route::any('/add-color/','Admins\DropdownsController@add_color');
	Route::any('/edit-color/{slug}','Admins\DropdownsController@edit_color');
	
	Route::any('/sizes/','Admins\DropdownsController@sizes')->name('dropdowns.sizes');
	Route::any('/add-size/','Admins\DropdownsController@add_size');
	Route::any('/edit-size/{slug}','Admins\DropdownsController@edit_size');
	
	Route::any('/gallery/','Admins\GalleryController@gallery')->name('gallery.gallery');
	Route::any('/upload-gallery-images/','Admins\GalleryController@upload_gallery_images')->name('gallery.upload_gallery_images');
	Route::any('/delete-gallery-image/','Admins\GalleryController@delete_gallery_image')->name('gallery.delete_gallery_image');
	
	Route::any('/banners/','Admins\GalleryController@banners')->name('gallery.banners');
	Route::any('/add-banner/','Admins\GalleryController@add_banner');
	Route::any('/edit-banner/{slug}','Admins\GalleryController@edit_banner');
	
	Route::any('/get-tags/','Admins\BlogsController@get_tags')->name('blogs.get_tags');
	
	Route::any('/removeZipcode/','Admins\LocationsController@removeZipcode')->name('locations.remove_zipcode');
	
	Route::any('/get-state/','Admins\AjaxController@get_state')->name('ajax.get_state');
	
	Route::any('/products/','Admins\DropdownsController@products')->name('dropdowns.products');
	
	Route::any('/changeStatus/','Admins\AjaxController@change_status')->name('ajax.change_status');
	
	Route::any('/updateOrder/','Admins\AjaxController@update_order')->name('ajax.update_order');
	
	Route::any('/deleteRecord/','Admins\AjaxController@delete_record')->name('ajax.delete_record');

});