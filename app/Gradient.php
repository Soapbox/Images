<?php

namespace App;

use Nesk\Puphpeteer\Puppeteer;

class Gradient
{


    /**
     * Create a new Gradient with the given colours
     *
     * @param string $start
     * @param string $end
     */
    public function __construct(private string $start, private string $end)
    {
    }

    /**
     * Render a this gradient to the given file
     *
     * @param string $file
     *
     * @return void
     */
    public function render(string $file): void
    {
        $browser = (new Puppeteer())->launch();

        $page = $browser->newPage();
        $page->setViewport(['width' => 192, 'height' => 192]);
        $page->goto(url("/i/gradient/{$this->start}/{$this->end}?ZRayDisable=1"));
        $page->screenshot(['path' => $file]);

        $browser->close();
    }

    /**
     * Find the colour that is in the middle of the start and end of this gradient
     *
     * @return string
     */
    public function findMidpoint(): string
    {
        [$startRed, $startGreen, $startBlue] = sscanf($this->start, '%02x%02x%02x');
        [$endRed, $endGreen, $endBlue] = sscanf($this->end, '%02x%02x%02x');

        return sprintf(
            '%02x%02x%02x',
            ($startRed + $endRed) / 2,
            ($startGreen + $endGreen) / 2,
            ($startBlue + $endBlue) / 2
        );
    }
}
