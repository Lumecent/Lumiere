<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateRule extends GenerateCommand
{
    protected $signature = 'lumiere:rule {rule} {mode=interactive} {container?}';

    protected $description = 'Создаёт новое правило валидации';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Rules" );

        $this->argument = 'rule';
        $this->namespace = "App\Containers\\$this->container\Rules";
        $this->stubFileName = 'rule';

        $this->createFile( 'Rule' );

        $this->info( 'Правило валидации создано!' );
    }
}
