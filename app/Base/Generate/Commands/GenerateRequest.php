<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateRequest extends GenerateCommand
{
    protected $signature = 'lumiere:request {request} {mode=interactive} {container?}';

    protected $description = 'Создаёт новый запрос формы';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Requests" );

        $this->argument = 'request';
        $this->namespace = "App\Containers\\$this->container\Requests";
        $this->stubFileName = 'request';

        $this->createFile( 'Request' );

        $this->info( 'Запрос формы создан!' );
    }
}
