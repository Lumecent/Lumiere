<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateException extends GenerateCommand
{
    protected $signature = 'lumiere:exception {exception} {mode=interactive} {container?}';

    protected $description = 'Создаёт новое исключение';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Exceptions" );

        $this->argument = 'exception';
        $this->namespace = "App\Containers\\$this->container\Exceptions";
        $this->stubFileName = 'exception';

        $this->createFile( 'Exception' );

        $this->info( 'Исключение создано!' );
    }
}