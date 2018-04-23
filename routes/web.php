<?php

use Emojione\Client;
use Emojione\Ruleset;
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
    
    //if the user has entered text to render
    if (strlen($image) > 0) {
        $imageStr = mb_substr($image,0,1); //grab only the first character
        
        //if alphanumeric - capitalize and use Open Sans. Cause it's a dope font
        if (ctype_alnum($imageStr)) {
            $imageStr = mb_convert_case($imageStr, MB_CASE_UPPER);
            
            $img->text($imageStr, 96, 96, function($font) {
                $font->file(storage_path('open-sans.bold.ttf'));
                $font->size(136);
                $font->color('#fff');
                $font->align('center');
                $font->valign('middle');
            });  
        } else {
            //if non-alphanumeric
            
            //if is emojii, render the image.
            $emojione = new Client(new Ruleset());
            $emojione->emojiSize = 128;
            $shortCode = $emojione->toShort($imageStr);

            if(mb_strlen($shortCode) > 1) {
                $emoji = $emojione->toImage($imageStr);
                $xpath = new DOMXPath(@DOMDocument::loadHTML($emoji));
                $src = $xpath->evaluate("string(//img/@src)");
                $emojiImage = Image::make($src);
                $emojiImage->resize(100, 100);
                $img->insert($emojiImage, 'center');
            } else {
                //if it is not an emoji but is special 
                //do not capitalize and use Shitty Arial Unicode Font
                $img->text($imageStr, 96, 96, function($font) {
                    $font->file(storage_path('Arial Unicode MS.ttf'));
                    $font->size(110);
                    $font->color('#fff');
                    $font->align('center');
                    $font->valign('middle');
                }); 
            }
        } 
    }

    return $img->response('png');
})->where('image', '(.*)');
