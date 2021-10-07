<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;
use Carbon\Carbon;

class GenerateMigration extends GenerateCommand
{
    protected $signature = 'lumiere:migration {migration} {mode=interactive} {container?} {type?}';

    protected $description = 'Create a new migration';

    protected function interactiveMode(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucwords( strtolower( $this->choice( 'Specify the migration type', [ 'Create Table', 'Update Table' ] ) ) );

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function silentMode(): void
    {
        $container = ucfirst( strtolower( $this->argument( 'container' ) ) );
        if ( !$container ) {
            $this->error( "Enter container name!" );

            exit();
        }
        $this->checkContainer( $container );

        $type = strtolower( $this->argument( 'type' ) );
        if ( !in_array( $type, [ 'create', 'update' ] ) ) {
            $this->error( 'Migration type should be "create" or "update"' );

            exit();
        }

        if ( $type === 'create' ) {
            $type = 'Create Table';
        }
        else {
            $type = 'Update Table';
        }

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function processGenerateFile( $params ): void
    {
        [ $type, $container ] = $params;

        FilesystemHelper::createDir( "app/Containers/$container/Data" );
        FilesystemHelper::createDir( "app/Containers/$container/Data/Migrations" );

        if ( ucwords( $type ) === 'Create Table' ) {
            $stubFileName = 'migration.create';
        }
        else {
            $stubFileName = 'migration.update';
        }

        $this->createFile( [ 'migration', "App\Containers\\$container\Data\Migrations" ], $stubFileName );
        $this->info( 'Migration created!' );
    }

    public function createFile( array $params, string $stubFileName ): void
    {
        [ $argument, $namespace ] = $params;

        $className = strtolower( $this->argument( $argument ) );
        $tableName = "{$className}s";

        if ( str_contains( $stubFileName, 'create' ) ) {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_create_{$className}s_table.php" );
            $className = 'Create' . ucfirst( $className ) . 'sTable';
        }
        else {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_update_{$className}s_table.php" );
            $className = 'Update' . ucfirst( $className ) . 'sTable';
        }

        $replaces = [
            '{{ class }}' => $className,
            '{{ namespace }}' => $namespace,
            '{{ table }}' => $tableName
        ];

        $contentNewFile = $this->parseStubFile( $replaces, $stubFileName );
        FilesystemHelper::createFile( $fileName, $contentNewFile );
    }
}
