<?php

Route::get('/', function ()
{
    return new \Illuminate\Http\RedirectResponse('recipes');
});

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('recipes/import', ['as' => 'recipes.import', 'uses' => 'ImportRecipeController@create']);
Route::post('recipes/import', ['as' => 'recipes.fetch', 'uses' => 'ImportRecipeController@store']);
Route::resource('recipes', 'RecipeController');
