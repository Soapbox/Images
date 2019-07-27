<?php

namespace App\Http\Controllers;

use Exception;
use App\Gradient;
use Emojione\Ruleset;
use Illuminate\Support\Str;
use App\Filters\TextOverlay;
use App\Filters\EmojiOverlay;
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

    /**
     * Render a gradient with the given colours
     *
     * @param string $start
     * @param string $end
     *
     * @return void
     */
    public function renderGradient(string $start, string $end)
    {
        $start = strtoupper($start);
        $end = strtoupper($end);
        return view('channel-avatar', ['start' => $start, 'end' => $end]);
    }

    /**
     * Render the channel icon with the given gradient and emoji
     *
     * @param string $gradientStart
     * @param string $gradientEnd
     * @param string $emoji
     *
     * @return void
     */
    public function renderChannelIcon(string $gradientStart, string $gradientEnd, string $emoji)
    {
        $gradientStart = strtoupper($gradientStart);
        $gradientEnd = strtoupper($gradientEnd);

        $emoji = Str::start(Str::finish($emoji, ':'), ':');
        if (!array_key_exists($emoji, (new Ruleset())->getShortcodeReplace())) {
            abort(404);
        }

        $file = storage_path("gradients/$gradientStart-$gradientEnd.png");
        if (!file_exists($file)) {
            $this->renderGradient($gradientStart, $gradientEnd, $file);
            try {
                (new Gradient($gradientStart, $gradientEnd))->render($file);
            } catch (Exception $e) {
            }
        }

        if (file_exists($file)) {
            $image = Image::make($file);
            $image->resize(192, 192);
        } else {
            $image = Image::canvas(192, 192, (new Gradient($gradientStart, $gradientEnd))->findMidpoint());
        }

        $image->filter(new EmojiOverlay($emoji));

        return $image->response('png');
    }
}
