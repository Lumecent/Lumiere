<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Carbon\Carbon;

class GenerateMigration extends GenerateCommand
{
    protected $signature = 'lumiere:migration {migration} {mode=interactive} {container?} {type?*}';

    protected $description = 'Create a new migration';

    protected function interactiveMode(): void
    {
        $container = ucfirst(( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucwords( strtolower( $this->choice( 'Specify the migration type', [ 'Create Table', 'Update Table' ] ) ) );

        $this->processGenerateFile( [ $type, $container ] );
    }

    protected function silentMode(): void
    {
        $container = ucfirst( ( $this->argument( 'container' ) ) );
        if ( !$container ) {
            $this->error( "Enter container name!" );

            exit();
        }
        $this->checkContainer( $container );

        $argument = $this->argument( 'type' );
        if ( is_array( $argument ) ) {
            $argument = array_map( static fn( string $string ) => strtolower( $string ), $argument );

            $type = implode( ' ', $argument );
        }
        else {
            $type = strtolower( $argument );
        }

        if ( !in_array( $type, [ 'create table', 'update table' ] ) ) {
            $this->error( 'Migration type should be "create" or "update"' );

            exit();
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

    public function createFile( array $params, string $stubFileName, string $classPostfix = '' ): void
    {
        [ $argument, $namespace ] = $params;

        $tableName = strtolower( preg_replace( '/[A-Z][a-z]+/', '_$0', lcfirst( $this->argument( $argument ) ) ) ) . 's';

        if ( str_contains( $stubFileName, 'create' ) ) {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_create_{$tableName}_table.php" );
        }
        else {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_update_{$tableName}_table.php" );
        }

        $replaces = [
            '{{ namespace }}' => $namespace,
            '{{ table }}' => $tableName
        ];

        $contentNewFile = $this->parseStubFile( $replaces, $stubFileName );
        FilesystemHelper::createFile( str_replace( '\\', '/', $fileName ), $contentNewFile );
    }
}
