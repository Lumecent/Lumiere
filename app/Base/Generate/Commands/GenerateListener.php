<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateListener extends GenerateCommand
{
    protected $signature = 'lumiere:listener {listener} {mode=interactive} {container?}';

    protected $description = 'Создаёт нового прослушивателя событий';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Listeners" );

        $this->argument = 'listener';
        $this->namespace = "App\Containers\\$this->container\Listeners";
        $this->stubFileName = 'listener';

        $this->createFile( 'Listener' );

        $this->info( 'Прослушиватель событий создан!' );
    }
}