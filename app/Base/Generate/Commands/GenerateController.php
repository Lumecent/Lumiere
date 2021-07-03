<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateController extends GenerateCommand
{
    protected $signature = 'lumiere:controller {controller}';

    protected $description = 'Create a new controllers';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucfirst( strtolower( $this->choice( 'Specify the controller type', [ 'Api', 'Web', 'Console' ] ) ) );

        FilesystemHelper::createDir( "app/Containers/$container/Controllers" );
        FilesystemHelper::createDir( "app/Containers/$container/Controllers/" . ucfirst( $type ) );

        $this->createFile( 'controller', "App\Containers\\$container\Controllers\\$type", 'controller.' . strtolower( $type ) );
        $this->info( 'Controller created!' );
    }
}
