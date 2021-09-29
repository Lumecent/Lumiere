<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;
use Carbon\Carbon;

class GenerateMigration extends GenerateCommand
{
    protected $signature = 'lumiere:migration {migration}';

    protected $description = 'Create a new migration';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        $type = ucfirst( strtolower( $this->choice( 'Specify the migration type', [ 'Create Table', 'Update Table' ] ) ) );

        FilesystemHelper::createDir( "app/Containers/$container/Data" );
        FilesystemHelper::createDir( "app/Containers/$container/Data/Migrations" );

        if ( ucwords( $type ) === 'Create Table' ) {
            $stubFileName = 'migration.create';
        }
        else {
            $stubFileName = 'migration.update';
        }

        $this->createFile( 'migration', "App\Containers\\$container\Data\Migrations", $stubFileName );
        $this->info( 'Model created!' );
    }

    public function createFile( string $argument, string $namespace, string $stubFileName ): void
    {
        $argumentName = strtolower( $this->argument( $argument ) );
        $tableName = "{$argumentName}s";

        if ( str_contains( $stubFileName, 'create' ) ) {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_create_{$argumentName}s_table.php" );
            $argumentName = 'Create' . ucfirst( $argumentName ) . 'sTable';
        }
        else {
            $fileName = lcfirst( $namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_update_{$argumentName}s_table.php" );
            $argumentName = 'Update' . ucfirst( $argumentName ) . 'sTable';
        }

        $contentNewFile = $this->parseStubFile(
            $argumentName,
            $namespace,
            $stubFileName
        );

        $contentNewFile = str_replace( '{{ table }}', $tableName, $contentNewFile );
        FilesystemHelper::createFile( $fileName, $contentNewFile );
    }
}
