<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('/new_registration', 'UsersController@new_registration');
    Route::post('users/register', 'UsersController@register');
    Route::post('users/authenticate', 'LoginController@authenticate');
    Route::post('forget-password', 'LoginController@forget_password');
    Route::post('reset-password', 'LoginController@reset_password');
    Route::get('pdf2', 'ProductController@pdf2');

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Login and Forget Pasword
// Route::post('users/register', 'UsersController@register');
// Route::post('users/authenticate', 'LoginController@authenticate');
// Route::post('forget-password', 'LoginController@forget_password');
// Route::post('reset-password', 'LoginController@reset_password');
// Route::get('pdf2', 'ProductController@pdf2');

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    //Users
    Route::get('users', 'UsersController@users');
    Route::get('getuser/{id}', 'UsersController@getuser');
    Route::post('edit-user', 'UsersController@edit_user');
    Route::get('deleteUser/{id}', 'UsersController@deleteUser');
    Route::get('users/logout', 'UsersController@logout');
    Route::post('change-password', 'UsersController@change_password');

    //Client
    Route::get('clients', 'ClientController@clients');
    Route::post('add-client', 'ClientController@add_client');
    Route::get('getclient/{id}', 'ClientController@getclient');
    Route::post('edit-client', 'ClientController@edit_client');
    // Route::get('deleteClient/{id}', 'ClientController@deleteClient');

    //Customers
    Route::post('customers', 'CustomerController@customers');
    Route::get('customersList', 'CustomerController@customersList');
    Route::post('add-customer', 'CustomerController@add_customer');  
    Route::get('get-customer-info/{id}', 'CustomerController@get_customer_info');
    Route::post('search-customer', 'CustomerController@search_customer');
    Route::post('edit-customer', 'CustomerController@edit_customer');
    Route::get('deleteCustomer/{id}', 'CustomerController@deleteCustomer');
    Route::get('customersgrid/{pagenumber}', 'CustomerController@customersgrid');
    
    //Project
    Route::post('projects', 'ProjectController@projects');
    Route::get('projectList', 'ProjectController@projectList');
    Route::post('add-project', 'ProjectController@add_project');
    Route::post('search-project', 'ProjectController@search_project');
    Route::get('get-project-info/{id}', 'ProjectController@get_project_info');
    Route::post('edit-project', 'ProjectController@edit_project');
    Route::get('deleteProject/{id}', 'ProjectController@deleteProject');
    Route::get('delete_project_doc/{id}', 'ProjectController@delete_project_doc');
    Route::get('projectgrid/{pagenumber}', 'ProjectController@projectgrid');

    //Product
    Route::post('products', 'ProductController@products');
    Route::get('productList', 'ProductController@productList'); 
    Route::post('add-product', 'ProductController@add_product');
    Route::post('search-product', 'ProductController@search_product');
    Route::get('get-product-info/{id}', 'ProductController@get_product_info');
    Route::post('edit-product', 'ProductController@edit_product');
    Route::get('deleteProduct/{id}', 'ProductController@deleteProduct');
    Route::get('delete_product_doc/{id}', 'ProjectController@delete_product_doc');
    Route::get('filter-data', 'ProductController@filter_product');
    Route::post('pdf', 'ProductController@pdf');
    Route::get('productgrid/{pagenumber}/{project_id?}', 'ProductController@productgrid');
    
    

    //Categories
    Route::get('product-categories', 'ProductCategoryController@product_categories');
    Route::post('add-product-category', 'ProductCategoryController@add_product_category');
    Route::post('edit-product-category', 'ProductCategoryController@edit_product_category');
    Route::get('project-categories', 'ProjectCategoryController@project_categories');
    Route::post('add-project-category', 'ProjectCategoryController@add_project_category');
    Route::post('edit-project-category', 'ProjectCategoryController@edit_project_category');
    
});