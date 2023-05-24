<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateException extends GenerateCommand
{
    protected $signature = 'lumiere:exception {exception} {mode=interactive} {container?}';

    protected $description = 'Create a new exception';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Exceptions" );

        $this->createFile( [ 'exception', "App\Containers\\$container\Exceptions" ], 'exception', 'Exception' );
        $this->info( 'Exception created!' );
    }
}