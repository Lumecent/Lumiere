<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRule extends GenerateCommand
{
    protected $signature = 'lumiere:rule {rule} {mode=interactive} {container?}';

    protected $description = 'Create a new rules';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Rules" );

        $this->createFile( [ 'rule', "App\Containers\\$container\Rules" ], 'rule', 'Rule' );
        $this->info( 'Rule created!' );
    }
}
