<?php

namespace App\Abstractions\Testing;

use Illuminate\Http\Testing\FileFactory as IlluminateFileFactory;

class FileFactory extends IlluminateFileFactory
{
    public function image($name, $width = 10, $height = 10): File
    {
        return new File($name, $this->generateImage(
            $width, $height, pathinfo($name, PATHINFO_EXTENSION)
        ));
    }
}