<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateSeeder extends GenerateCommand
{
    protected $signature = 'lumiere:seeder {seeder} {mode=interactive} {container?}';

    protected $description = 'Create a new seeder';

    protected function processGenerateFile( $params ): void
    {
        [ $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Data" );
        FilesystemHelper::createDir( "app/Containers/$container/Seeders" );

        $this->createFile( [ 'seeder', "App\Containers\\$container\Data\Seeders" ], 'seeder', 'Seeder' );
        $this->info( 'Seeder created!' );
    }
}