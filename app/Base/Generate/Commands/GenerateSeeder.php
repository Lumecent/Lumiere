<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateSeeder extends GenerateCommand
{
    protected $signature = 'lumiere:seeder {seeder} {mode=interactive} {container?}';

    protected $description = 'Создаёт новый сид';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Data/Seeders" );

        $this->argument = 'seeder';
        $this->namespace = "App\Containers\\$this->container\Data\Seeders";
        $this->stubFileName = 'seeder';

        $this->createFile( 'Seeder' );

        $this->info( 'Сид создан!' );
    }
}