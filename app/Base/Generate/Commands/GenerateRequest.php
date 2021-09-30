<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRequest extends GenerateCommand
{
    protected $signature = 'lumiere:request {request} {mode=interactive} {container?}';

    protected $description = 'Create a new requests';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Requests" );

        $this->createFile( 'request', "App\Containers\\$container\Requests", 'request' );
        $this->info( 'Request created!' );
    }
}
