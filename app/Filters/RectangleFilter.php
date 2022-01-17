<?php

namespace App\Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class RectangleFilter implements FilterInterface
{
    const DEFAULT_SIZE = 10;
    public function applyFilter(Image $image)
    {
        // TODO: Implement applyFilter() method.
    }
}
