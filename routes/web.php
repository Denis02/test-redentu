<?php

Route::get('/', 'ImportExcelController@index');
Route::post('/', 'ImportExcelController@store')->name('import');