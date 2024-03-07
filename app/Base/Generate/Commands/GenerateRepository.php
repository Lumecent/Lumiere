<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateRepository extends GenerateCommand
{
    protected $signature = 'lumiere:repository {repository} {mode=interactive} {container?}';

    protected $description = 'Создаёт новый репозиторий';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Repositories" );

        $this->argument = 'repository';
        $this->namespace = "App\Containers\\$this->container\Repositories";
        $this->stubFileName = 'repository';

        $this->createFile( 'Repository' );

        $this->info( 'Репозиторий создан!' );
    }
}
