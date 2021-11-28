<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateDto extends GenerateCommand
{
    protected $signature = 'lumiere:dto {dto} {mode=interactive} {container?}';

    protected $description = 'Create a new factories';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/DTO" );

        $this->createFile( [ 'dto', "App\Containers\\$container\DTO" ], 'dto', 'Dto' );
        $this->info( 'Dto created!' );
    }
}