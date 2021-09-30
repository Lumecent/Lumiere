<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateController extends GenerateCommand
{
    protected $signature = 'lumiere:controller {controller} {mode=interactive} {container?} {type?}';

    protected $description = 'Create a new controllers';

    protected function interactiveMode(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucfirst( strtolower( $this->choice( 'Specify the controller type', [ 'Api', 'Web', 'Console' ] ) ) );

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
        if ( !in_array( $type, [ 'api', 'web', 'console' ] ) ) {
            $this->error( 'Controller type should be "api", "web" or "console"' );

            exit();
        }

        $type = ucfirst( $type );

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function processGenerateFile( $params ): void
    {
        [ $type, $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Controllers" );
        FilesystemHelper::createDir( "app/Containers/$container/Controllers/" . ucfirst( $type ) );

        $this->createFile( 'controller', "App\Containers\\$container\Controllers\\$type", 'controller.' . strtolower( $type ) );
        $this->info( 'Controller created!' );
    }
}
