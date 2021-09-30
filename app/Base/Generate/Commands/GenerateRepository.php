<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRepository extends GenerateCommand
{
    protected $signature = 'lumiere:repository {repository} {mode=interactive} {container?}';

    protected $description = 'Create a new repositories';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Repositories" );

        $this->createFile( 'repository', "app\Containers\\$container\Repositories", 'repository' );
        $this->info( 'Repository created!' );
    }
}
