<?php

Route::get('/', function ()
{
    return new \Illuminate\Http\RedirectResponse('recipe');
});

Route::resource('recipes', 'RecipeController');
