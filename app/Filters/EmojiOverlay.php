<?php

namespace App\Filters;

use Intervention\Image\Image;

class EmojiOverlay extends TextOverlay
{
    /**
     * Creates new instance of filter with the emoji to overlay
     *
     * @param string $emoji
     */
    public function __construct(string $emoji)
    {
        $this->emoji = $emoji;
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
