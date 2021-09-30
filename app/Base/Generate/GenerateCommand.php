<?php

namespace App\Base\Generate;

use App\Abstractions\Commands\ConsoleCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateCommand extends ConsoleCommand
{
    private string $stubPath = 'app/Base/Generate/Stubs';

    protected function interactiveMode(): void
    {
    }

    protected function silentMode(): void
    {
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

    public function parseStubFile( string $className, string $namespace, string $stubFileName ): string
    {
        $content = FilesystemHelper::getContentFile( "$this->stubPath/$stubFileName.stub" );
        return str_replace( [ '{{ class }}', '{{ namespace }}' ], [ $className, $namespace ], $content );
    }

    public function checkContainer( string $container ): void
    {
        if ( !FilesystemHelper::existsDir( 'app/Containers/' . $container ) ) {
            $this->error( "Container '$container' not found" );

            exit();
        }
    }

    public function createFile( string $argument, string $namespace, string $stubFileName ): void
    {
        $argumentName = ucfirst( $this->argument( $argument ) );
        $fileName = lcfirst( "$namespace\\$argumentName.php" );
        if ( FilesystemHelper::existsFile( $fileName ) ) {
            $this->error( $fileName . ' already exists!' );

            exit();
        }

        $contentNewFile = $this->parseStubFile(
            $argumentName,
            $namespace,
            $stubFileName
        );
        FilesystemHelper::createFile( $fileName, $contentNewFile );
    }
}
