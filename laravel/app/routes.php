<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where we register all of the routes for an application.
|
*/

//:: User Account Routes ::
Route::post('user/login', 'UserController@postLogin');



Route::get('/', array('before' => 'auth','uses' => 'AdminController@getHome'));


# Client Management
Route::get('clients', array('before' => 'auth|av','uses' => 'AdminClientsController@getClients'));
Route::get('clients/data', array('before' => 'auth|av','uses' => 'AdminClientsController@getData'));
Route::get('clients/data/{client_id}', array('before' => 'auth|av','uses' => 'AdminClientsController@getData'));
Route::get('client/{client_id}', array('before' => 'auth|client','uses' => 'AdminClientsController@getClient'));

# Dealer Management
Route::get('client/{client_id}/dealers', array('before' => 'auth','uses' => 'AdminDealersController@getDealers'));
Route::get('client/{client_id}/dealers/data', array('before' => 'auth','uses' => 'AdminDealersController@getData'));
Route::get('client/{client_id}/dealers/data/{dealer_id}', array('before' => 'auth','uses' => 'AdminDealersController@getData'));
Route::get('client/{client_id}/dealer/{dealer_id}', array('before' => 'auth','uses' => 'AdminDealersController@getDealer'));

# User Management
Route::get('client/{client_id}/dealer/{dealer_id}/users', array('before' => 'auth','uses' => 'AdminUsersController@getUsers'));
Route::get('client/{client_id}/dealer/{dealer_id}/users/data', array('before' => 'auth','uses' => 'AdminUsersController@getData'));
Route::get('client/{client_id}/dealer/{dealer_id}/users/data/{user_id}', array('before' => 'auth','uses' => 'AdminUsersController@getData'));
Route::get('client/{client_id}/dealer/{dealer_id}/user/{user_id}', array('before' => 'auth','uses' => 'AdminUsersController@getUser'));
# User RESTful Routes (Login, Logout, Register, etc)
Route::controller('user', 'UserController');
Route::controller('admin', 'AdminController');




