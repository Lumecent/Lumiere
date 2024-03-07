<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateInterface extends GenerateCommand
{
    protected $signature = 'lumiere:interface {interface} {mode=interactive} {container?} {--m}';

    protected $description = 'Создаёт новый интерфейс';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Interfaces" );

        $this->argument = 'interface';
        $this->namespace = "App\Containers\\$this->container\Interfaces";
        $this->stubFileName = 'interface';

        $this->createFile( 'Interface' );

        $this->info( 'Интерфейс создан!' );
    }
}
