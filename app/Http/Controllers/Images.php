<?php

namespace App\Http\Controllers;

use App\Filters\TextOverlay;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Images extends Controller
{
    /**
     * Generates an image based on the provided name.
     *
     * @param string $name
     *
     * @return void
     */
    public function generate(string $name)
    {
        $file = Cache::get($name, function () use ($name) {
            $files = Storage::files('i');

            $hash = crc32($name);
            $name = $hash % count($files);
            $name = $files[$name];
            return $name;
        });

        $image = Image::make(storage_path(sprintf('app/%s', $file)));
        $image->filter(new TextOverlay($name));

        return $image->response('png');
    }
}
