<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRoute extends GenerateCommand
{
    protected $signature = 'lumiere:route {route}';

    protected $description = 'Create a new routes';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucfirst( strtolower( $this->choice( 'Specify the route type', [ 'Api', 'Web' ] ) ) );

        FilesystemHelper::createDir( "app/Containers/$container/Routes" );
        FilesystemHelper::createDir( "app/Containers/$container/Routes/" . ucfirst( $type ) );

        $this->createFile( 'route', "App\Containers\\$container\Routes\\$type", 'route.' . strtolower( $type ) );
        $this->info( 'Route created!' );
    }
}
