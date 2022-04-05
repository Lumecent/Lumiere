<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateFactories extends GenerateCommand
{
    protected $signature = 'lumiere:factories {factories} {mode=interactive} {container?}';

    protected $description = 'Create a new factories';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Factories" );

        $this->createFile( [ 'factories', "App\Containers\\$container\Factories" ], 'dto.factory', 'DtoFactory' );
        $this->createFile( [ 'factories', "App\Containers\\$container\Factories" ], 'service.factory', 'ServiceFactory' );
        $this->info( 'Factories created!' );
    }
}
