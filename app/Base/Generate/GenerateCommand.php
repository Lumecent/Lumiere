<?php

namespace App\Base\Generate;

use App\Abstractions\Commands\ConsoleCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateCommand extends ConsoleCommand
{
    private string $stubPath = 'app/Base/Generate/Stubs';

    public function parseStubFile( string $className, string $namespace, string $stubFileName ): string
    {
        $content = FilesystemHelper::getContentFile( "$this->stubPath/$stubFileName.stub" );
        return str_replace( [ '{{ class }}', '{{ namespace }}' ], [ $className, $namespace ], $content );
    }

    public function checkContainer( string $container ): void
    {
        if ( !FilesystemHelper::existsDir( 'app/Containers/' . $container ) ) {
            $this->error( "Container '$container' not found" );

            exit();
        }
    }
}
