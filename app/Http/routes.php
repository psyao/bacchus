<?php

Route::get('/', function ()
{
    return new \Illuminate\Http\RedirectResponse('recipes');
});

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::resource('recipes', 'RecipeController');
