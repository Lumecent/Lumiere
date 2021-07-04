<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateModel extends GenerateCommand
{
    protected $signature = 'lumiere:model {model}';

    protected $description = 'Create a new models';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        FilesystemHelper::createDir( "app/Containers/$container/Models" );

        $this->createFile( 'model', "App\Containers\\$container\Models", 'model' );
        $this->info( 'Model created!' );

        if ( $this->confirm( 'Create a new migration?' ) ) {
            Artisan::call( 'make:migration create_' . strtolower( preg_replace( '/(?<!^)[A-Z]+|(?<!^|\d)[\d]+/', '_$0', $this->argument( 'model' ) ) ) . 's_table' );
            $this->info( 'Migration created!' );
        }
    }
}
