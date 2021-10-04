<?php

namespace App\Abstractions\Seeders;

use Illuminate\Database\Seeder as IlluminateSeeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;

abstract class Seeder extends IlluminateSeeder
{
    /**
     * Seed the application's database.
     *
     */
    public function call( $class, $silent = false, array $parameters = [] )
    {
        $seedersDirsList = Cache::get( 'seedersDirsList', function () {
            $filesystem = new Filesystem();
            $seedersDirsList = [];

            foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $container ) {
                $containerPath = explode( '/', str_replace( '\\', '/', $container ) );
                $containerName = array_pop( $containerPath );

                if ( $filesystem->exists( $container . '/Data/Seeders' ) ) {
                    foreach ( $filesystem->files( $container . '/Data/Seeders' ) as $routeFile ) {
                        $seedersDirsList[] = '\App\Containers\\' . $containerName . '\Data\Seeders\\' . str_replace( '.php', '', $routeFile->getFilename() );
                    }
                }
            }
            Cache::put( 'seedersDirsList', $seedersDirsList, 120 );
            return $seedersDirsList;
        } );

        foreach ( $seedersDirsList as $m ) {
            parent::call( $m, $silent, $parameters );
        }
    }
}