<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateProvider extends GenerateCommand
{
    protected $signature = 'lumiere:provider {provider} {mode=interactive} {container?}';

    protected $description = 'Создаёт нового провайдера';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Providers" );

        $this->argument = 'provider';
        $this->namespace = "App\Containers\\$this->container\Providers";
        $this->stubFileName = 'provider';

        $this->createFile( 'ServiceProvider' );

        $this->info( 'Провайдер создан!' );
    }
}
