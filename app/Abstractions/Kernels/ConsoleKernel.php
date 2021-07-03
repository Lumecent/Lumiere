<?php

namespace App\Abstractions\Kernels;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\Kernel as IlluminateConsoleKernel;
use Illuminate\Support\Facades\Cache;

abstract class ConsoleKernel extends IlluminateConsoleKernel
{
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $commandsDirsList = Cache::get( 'commandsDirsList', function () {
            $commandsDirsList = [];

            $commandsDirsList = array_merge( $commandsDirsList, $this->registerBaseCommands() );
            $commandsDirsList = array_merge( $commandsDirsList, $this->registerGenerateCommands() );
            $commandsDirsList = array_merge( $commandsDirsList, $this->registerContainersCommands() );

            Cache::put( 'commandsDirsList', $commandsDirsList, 120 );
            return $commandsDirsList;
        } );

        $this->load( $commandsDirsList );
    }

    private function registerBaseCommands(): array
    {
        return [ 'App/Base/Commands' ];
    }

    private function registerGenerateCommands(): array
    {
        return [ 'App/Base/Generate/Commands' ];
    }

    private function registerContainersCommands(): array
    {
        $filesystem = new Filesystem();
        $commandsDirsList = [];

        foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $directory ) {
            $commandsDir = $directory . '/Controllers/Console';
            if ( is_dir( $commandsDir ) ) {
                $commandsDirsList[] = $commandsDir;
            }
        }
        return $commandsDirsList;
    }
}
