<?php

namespace App\Base\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class MigrationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $filesystem = new Filesystem();
        $migrationsDirsList = [];

        foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $directory ) {
            $migrationsDir = $directory . '/Data/Migrations';
            if ( $filesystem->isDirectory( $migrationsDir ) ) {
                $migrationsDirsList[] = $migrationsDir;
            }
        }

        $this->loadMigrationsFrom( $migrationsDirsList );
    }
}