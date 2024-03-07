<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateResource extends GenerateCommand
{
    protected $signature = 'lumiere:resource {resource} {mode=interactive} {container?} {type?}';

    protected $description = 'Создаёт новый ресурс';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Resources" );

        $this->argument = 'resource';
        $this->namespace = "App\Containers\\$this->container\Resources";
        $this->stubFileName = 'resource';

        $this->createFile( 'Resource' );

        $this->info( 'Ресурс создан!' );
    }
}
