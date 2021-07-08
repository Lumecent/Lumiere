<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRepository extends GenerateCommand
{
    protected $signature = 'lumiere:repository {repository}';

    protected $description = 'Create a new repositories';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        FilesystemHelper::createDir( "app/Containers/$container/Repositories" );

        $this->createFile( 'repository', "app\Containers\\$container\Repositories", 'repository' );
        $this->info( 'Repository created!' );
    }
}
