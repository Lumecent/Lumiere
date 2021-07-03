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

        $controller = ucfirst( $this->argument( 'controller' ) );
        $namespace = "App\Containers\\$container\Controllers\\$type";
        $nameController = lcfirst( "$namespace\\$controller.php" );
        if ( FilesystemHelper::existsFile( $nameController ) ) {
            $this->error( $nameController . ' already exists!' );
            return;
        }

        FilesystemHelper::createDir( "app/Containers/$container/Controllers" );
        FilesystemHelper::createDir( "app/Containers/$container/Controllers/" . ucfirst( $type ) );

        $contentController = $this->parseStubFile(
            $controller,
            $namespace,
            'controller.' . strtolower( $type )
        );
        FilesystemHelper::createFile( $nameController, $contentController );

        $this->info( 'Controller created!' );
    }
}
