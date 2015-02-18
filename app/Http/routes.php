<?php

Route::get('/', function ()
{
    return new \Illuminate\Http\RedirectResponse('recipes');
});

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('recipes/import', ['as' => 'recipes.provide', 'uses' => 'RecipeController@provide']);
Route::post('recipes/import', ['as' => 'recipes.import', 'uses' => 'RecipeController@import']);
Route::resource('recipes', 'RecipeController');
