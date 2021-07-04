<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRequest extends GenerateCommand
{
    protected $signature = 'lumiere:request {request}';

    protected $description = 'Create a new requests';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        FilesystemHelper::createDir( "app/Containers/$container/Requests" );

        $this->createFile( 'request', "App\Containers\\$container\Requests", 'request' );
        $this->info( 'Request created!' );
    }
}
