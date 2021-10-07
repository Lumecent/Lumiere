<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateModel extends GenerateCommand
{
    protected $signature = 'lumiere:model {model} {mode=interactive} {container?} {--m}';

    protected $description = 'Create a new models';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Models" );

        $this->createFile( [ 'model', "App\Containers\\$container\Models" ], 'model' );
        $this->info( 'Model created!' );

        if ( $this->option( 'm' ) ) {
            Artisan::call( "lumiere:migration {$this->argument('model')} silent $container create" );
            $this->info( 'Migration created!' );
        }
    }
}
