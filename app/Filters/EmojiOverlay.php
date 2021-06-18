<?php

namespace App\Filters;

use Intervention\Image\Image;

class EmojiOverlay extends TextOverlay
{
    public function __construct(private string $emoji)
    {
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
        return $this->overlayEmoji($this->emoji, $image);
    }
}
