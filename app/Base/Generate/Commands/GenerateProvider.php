<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateProvider extends GenerateCommand
{
    protected $signature = 'lumiere:provider {provider} {mode=interactive} {container?}';

    protected $description = 'Create a new providers';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Providers" );

        $this->createFile( [ 'provider', "App\Containers\\$container\Providers" ], 'provider', 'Provider' );
        $this->info( 'Provider created!' );
    }
}
