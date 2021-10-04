<?php

namespace App\Base\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class MigrationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $migrationsDirsList = Cache::get( 'migrationsDirsList', function () {
            $filesystem = new Filesystem();
            $migrationsDirsList = [];

            foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $directory ) {
                $migrationsDir = $directory . '/Data/Migrations';
                if ( $filesystem->isDirectory( $migrationsDir ) ) {
                    $migrationsDirsList[] = $migrationsDir;
                }
            }
            Cache::put( 'migrationsDirsList', $migrationsDirsList, 120 );
            return $migrationsDirsList;
        } );

        $this->loadMigrationsFrom( $migrationsDirsList );
    }
}