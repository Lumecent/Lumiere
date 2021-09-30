<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRoute extends GenerateCommand
{
    protected $signature = 'lumiere:route {route} {mode=interactive} {container?} {type?}';

    protected $description = 'Create a new routes';

    protected function interactiveMode(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucfirst( strtolower( $this->choice( 'Specify the route type', [ 'Api', 'Web' ] ) ) );

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function silentMode(): void
    {
        $container = ucfirst( strtolower( $this->argument( 'container' ) ) );
        if ( !$container ) {
            $this->error( "Enter container name!" );

            exit();
        }
        $this->checkContainer( $container );

        $type = strtolower( $this->argument( 'type' ) );
        if ( !in_array( $type, [ 'api', 'web' ] ) ) {
            $this->error( 'Route type should be "api" or "web"' );

            exit();
        }

        $type = ucfirst( $type );

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function processGenerateFile( $params ): void
    {
        [ $type, $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Routes" );
        FilesystemHelper::createDir( "app/Containers/$container/Routes/" . ucfirst( $type ) );

        $this->createFile( 'route', "App\Containers\\$container\Routes\\$type", 'route.' . strtolower( $type ) );
        $this->info( 'Route created!' );
    }
}
