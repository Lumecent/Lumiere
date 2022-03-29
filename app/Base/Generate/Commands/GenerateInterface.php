<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateInterface extends GenerateCommand
{
    protected $signature = 'lumiere:interface {interface} {mode=interactive} {container?} {--m}';

    protected $description = 'Create a new interfaces';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Interfaces" );

        $this->createFile( [ 'interface', "App\Containers\\$container\Interfaces" ], 'interface', 'Interface' );
        $this->info( 'Interface created!' );
    }
}
