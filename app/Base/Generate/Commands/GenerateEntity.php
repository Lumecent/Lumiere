<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateEntity extends GenerateCommand
{
    protected $signature = 'lumiere:entity {entity} {mode=interactive} {container?} {--m}';

    protected $description = 'Create a new entities';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Entities" );

        $this->createFile( [ 'entity', "App\Containers\\$container\Entities" ], 'entity', 'Entity' );
        $this->info( 'Entity created!' );
    }
}
