<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateDto extends GenerateCommand
{
    protected $signature = 'lumiere:dto {dto} {mode=interactive} {container?}';

    protected $description = 'Создаёт новый DTO';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Dto" );

        $this->argument = 'dto';
        $this->namespace = "App\Containers\\$this->container\Dto";
        $this->stubFileName = 'dto';

        $this->createFile( 'Dto' );

        $this->info( 'DTO создан!' );
    }
}