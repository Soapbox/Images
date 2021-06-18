<?php

namespace App\Filters;

use DOMXPath;
use DOMDocument;
use JoyPixels\Client;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Facades\Image as ImageFacade;

class TextOverlay implements FilterInterface
{
    public const OPEN_SANS = 'open-sans.bold.ttf';
    public const ARIAL = 'Arial Unicode MS.ttf';

    public function __construct(private string $text = '')
    {
    }

    /**
     * Overlays the provided letter onto the image.
     *
     * @param string $letter
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    private function overlayLetter(string $letter, Image $image): Image
    {
        $letter = ($isAlpha = ctype_alnum($letter)) ? Str::ucfirst($letter) : $letter;

        $image->text($letter, 96, 96, function ($font) use ($isAlpha) {
            $font->file(storage_path($isAlpha ? self::OPEN_SANS : self::ARIAL));
            $font->size($isAlpha ? 136 : 110);
            $font->color('#fff');
            $font->align('center');
            $font->valign('middle');
        });

        return $image;
    }

    /**
     * Returns an image for the provided emoji.
     *
     * @param string $emoji
     *
     * @return \Intervention\Image\Image
     */
    private function getEmoji(string $emoji): Image
    {
        $client = resolve(Client::class);
        $client->emojiSize = 128;

        $doc = new DOMDocument();
        $doc->loadHTML($client->toImage($emoji));
        $xpath = new DOMXPath($doc);

        return ImageFacade::make($xpath->evaluate('string(//img/@src)'));
    }

    /**
     * Overlays the provided emoji onto the image.
     *
     * @param string $emoji
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    protected function overlayEmoji(string $emoji, Image $image): Image
    {
        $emojiImage = $this->getEmoji($emoji);
        $emojiImage->resize(100, 100);

        $image->insert($emojiImage, 'center');

        return $image;
    }

    /**
     * Applies filter effects to given image
     *
     * @param  \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $letter = Str::first($this->text);

        if (Str::startsWithEmoji($letter)) {
            return $this->overlayEmoji($letter, $image);
        }

        return $this->overlayLetter($letter, $image);
    }
}
