<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateResource extends GenerateCommand
{
    protected $signature = 'lumiere:resource {resource} {mode=interactive} {container?} {type?}';

    protected $description = 'Create a new resources';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Resources" );

        $this->createFile( [ 'resource', "App\Containers\\$container\Resources" ], 'resource', 'Resource' );
        $this->info( 'Resource created!' );
    }
}
