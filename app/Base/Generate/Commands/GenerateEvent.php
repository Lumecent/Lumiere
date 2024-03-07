<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateEvent extends GenerateCommand
{
    protected $signature = 'lumiere:event {event} {mode=interactive} {container?}';

    protected $description = 'Создаёт события';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Events" );

        $this->argument = 'event';
        $this->namespace = "App\Containers\\$this->container\Events";
        $this->stubFileName = 'event';

        $this->createFile( 'Event' );

        $this->info( 'Событие создано!' );
    }
}