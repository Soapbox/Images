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

        $hash = crc32($image);
        $image = $hash % count($files);
        $image = $files[$image];
        return $image;
    });

    $img = Image::make(storage_path(sprintf('app/%s', $file)));
    
    if (strlen($image) > 0) {
        $imageStr = mb_substr($image,0,1);
        
        $img->text($imageStr, 96, 96, function($font) {
            $font->file(storage_path('Arial Unicode MS.ttf'));
            $font->size(160);
            $font->color('#fff');
            $font->align('center');
            $font->valign('middle');
        });  
    }

    return $img->response('png');
})->where('image', '(.*)');
