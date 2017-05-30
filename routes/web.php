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

Route::get('/i/{image}', function ($image) {
    $file = Cache::get($image, function () use ($image) {
        $files = Storage::files('i');

        $hash = hash('sha512', $image);
        $image = ((int) $hash) % count($files);
        $image = $files[$image];

        return $image;
    });

    return response()->file(storage_path(sprintf('app/%s', $file)));
})->where('image', '(.*)');
