<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateService extends GenerateCommand
{
    protected $signature = 'lumiere:service {services} {mode=interactive} {service?}';

    protected $description = 'Create a new service';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Services" );

        $this->createFile( [ 'services', "App\Containers\\$container\Services" ], 'service', 'Service' );
        $this->info( 'Service created!' );
    }
}