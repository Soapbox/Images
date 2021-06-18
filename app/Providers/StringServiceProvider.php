<?php

namespace App\Providers;

use JoyPixels\Client;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class StringServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Str::macro('startsWithEmoji', fn (string $string) =>
             Str::length(resolve(Client::class)->toShort(Str::first($string))) > 1
        );

        Str::macro('first', fn (string $string) => Str::substr($string, 0, 1));
    }
}
