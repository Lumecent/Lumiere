<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Artisan;

class GenerateModel extends GenerateCommand
{
    protected $signature = 'lumiere:model {model} {mode=interactive} {container?} {--m}';

    protected $description = 'Создаёт новую модель';

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Models" );

        $this->argument = 'model';
        $this->namespace = "App\Containers\\$this->container\Models";
        $this->stubFileName = 'model';

        $this->createFile( 'Model' );

        $this->info( 'Модель создана!' );

        if ( $this->option( 'm' ) ) {
            Artisan::call( "lumiere:migration {$this->argument('model')} silent $this->container create" );

            $this->info( 'Миграция создана!' );
        }
    }
}
