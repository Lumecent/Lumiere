<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateFactory extends GenerateCommand
{
    protected $signature = 'lumiere:factory {factory} {mode=interactive} {container?} {model?}';

    protected $description = 'Create a new factories';

    protected function interactiveMode(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $model = ucfirst( strtolower( $this->ask( 'Specify the model name' ) ) );
        $modelPath = "app/Containers/$container/Models/$model.php";
        $this->checkModel( $modelPath );

        $this->processGenerateFile( [ $container, str_replace( [ '.php', '/' ], [ '', '\\' ], $modelPath ) ] );
    }

    protected function silentMode(): void
    {
        $container = ucfirst( strtolower( $this->argument( 'container' ) ) );
        if ( !$container ) {
            $this->error( "Enter container name!" );

            exit();
        }
        $this->checkContainer( $container );

        $model = ucfirst( strtolower( $this->argument( 'model' ) ) );
        if ( !$model ) {
            $this->error( "Enter model name!" );

            exit();
        }
        $modelPath = "app/Containers/$container/Models/$model.php";
        $this->checkModel( $modelPath );

        $this->processGenerateFile( [ $container, str_replace( [ '.php', '/' ], [ '', '\\' ], $modelPath ) ] );
    }

    protected function processGenerateFile( $params ): void
    {
        [ $container, $modelNamespace ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Data" );
        FilesystemHelper::createDir( "app/Containers/$container/Data/Factories" );

        $this->createFile( [
            'factory', "App\Containers\\$container\Data\Factories", $modelNamespace
        ], 'factory' );
        $this->info( 'Factory created!' );
    }

    public function createFile( array $params, string $stubFileName ): void
    {
        [ $argument, $namespace, $modelNamespace ] = $params;

        $argumentName = ucfirst( $this->argument( $argument ) );
        $argumentName = "{$argumentName}Factory";

        $modelNamespaceRaw = explode( '\\', $modelNamespace );
        $model = array_pop( $modelNamespaceRaw );

        $fileName = "$namespace\\$argumentName";
        if ( class_exists( $fileName ) ) {
            $this->error( $fileName . ' already exists!' );

            exit();
        }

        $contentNewFile = $this->parseStubFile(
            [
                '{{ class }}' => $argumentName,
                '{{ model }}' => $model,
                '{{ factoryNamespace }}' => $namespace,
                '{{ modelNamespace }}' => ucfirst( $modelNamespace )
            ],
            $stubFileName
        );

        FilesystemHelper::createFile( "$fileName.php", $contentNewFile );
    }

    private function checkModel( $modelPath ): void
    {
        if ( !FilesystemHelper::existsFile( $modelPath ) ) {
            $this->error( "Model '$modelPath' not found" );

            exit();
        }
    }
}