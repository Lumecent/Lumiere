<?php

namespace App\Base\Generate;

use App\Abstractions\Commands\ConsoleCommand;
use App\Utilities\Helpers\FilesystemHelper;

class GenerateCommand extends ConsoleCommand
{
    private string $stubPath = 'app/Base/Generate/stubs';

    protected function interactiveMode(): void
    {
        $container = ucfirst( $this->ask( 'Specify the container name' ) );
        $this->checkContainer( $container );

        $this->processGenerateFile( [ $container ] );
    }

    protected function silentMode(): void
    {
        $container = ucfirst( $this->argument( 'container' ) );
        if ( !$container ) {
            $this->error( "Enter container name!" );

            exit();
        }

        $this->processGenerateFile( [ $container ] );
    }

    protected function processGenerateFile( $params ): void
    {
    }

    public function handle(): void
    {
        if ( $this->argument( 'mode' ) === 'interactive' ) {
            $this->interactiveMode();
        }
        else {
            $this->silentMode();
        }
    }

    public function parseStubFile( array $replaces, string $stubFileName ): string
    {
        $search = array_keys( $replaces );
        $replace = array_values( $replaces );

        $content = FilesystemHelper::getContentFile( "$this->stubPath/$stubFileName.stub" );
        return str_replace( $search, $replace, $content );
    }

    public function checkContainer( string $container ): void
    {
        if ( !FilesystemHelper::existsDir( 'app/Containers/' . $container ) ) {
            $this->error( "Container '$container' not found" );

            exit();
        }
    }

    public function createFile( array $params, string $stubFileName, string $classPostfix = '' ): void
    {
        [ $argument, $namespace ] = $params;

        if ( $argument ) {
            $className = ucfirst( $this->argument( $argument ) ) . ucfirst( $classPostfix );
        }
        else {
            $classPath = explode( '\\', $namespace );
            $className = array_pop( $classPath ) . ucfirst( $classPostfix );

            $namespace = implode( '\\', $classPath );
        }

        if ( str_contains( $className, '/' ) ) {
            $directories = explode( '/', $className );

            $className = array_pop( $directories );

            foreach ( $directories as $directory ) {
                $namespace .= '\\' . $directory;

                $path = str_replace( '\\', '/', $namespace );
                if ( !FilesystemHelper::existsDir( $path ) ) {
                    FilesystemHelper::createDir( $path );
                }
            }
        }

        $fileName = "$namespace\\$className";
        if ( class_exists( $fileName ) ) {
            $this->error( $fileName . ' already exists!' );

            exit();
        }

        $replaces = [
            '{{ class }}' => $className,
            '{{ namespace }}' => $namespace
        ];

        $contentNewFile = $this->parseStubFile( $replaces, $stubFileName );
        FilesystemHelper::createFile( str_replace( '\\', '/', lcfirst( $fileName ) ) . ".php", $contentNewFile );
    }
}
