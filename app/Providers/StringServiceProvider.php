<?php

namespace App\Providers;

use Emojione\Client;
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
        Str::macro('startsWithEmoji', function (string $string) {
            return Str::length(resolve(Client::class)->toShort(Str::first($string))) > 1;
        });

        Str::macro('first', function (string $string) {
            return Str::substr($string, 0, 1);
        });
    }
}
