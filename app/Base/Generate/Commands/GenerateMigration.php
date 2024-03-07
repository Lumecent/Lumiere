<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateMigration extends GenerateCommand
{
    protected $signature = 'lumiere:migration {migration} {mode=interactive} {container?} {type?*}';

    protected $description = 'Создаёт новую миграцию';

    private ?string $type = null;

    private array $migrationTypes = [
        'create',
        'update'
    ];

    public function createFile( string $classPostfix = '' ): void
    {
        $tableName = strtolower( preg_replace( '/[A-Z][a-z]+/', '_$0', lcfirst( $this->argument( $this->argument ) ) ) ) . 's';

        if ( str_contains( $this->stubFileName, 'create' ) ) {
            $fileName = lcfirst( $this->namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_create_{$tableName}_table.php" );
        }
        else {
            $fileName = lcfirst( $this->namespace . '\\' . Carbon::now()->format( 'Y_m_d_His' ) . "_update_{$tableName}_table.php" );
        }

        $this->replaces = [
            '{{ namespace }}' => $this->namespace,
            '{{ table }}' => $tableName
        ];

        $contentNewFile = $this->parseStubFile();

        FilesystemHelper::createFile( str_replace( '\\', '/', $fileName ), $contentNewFile );
    }

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->type = ucwords( strtolower( $this->choice( 'Укажите тип миграции', $this->migrationTypes ) ) );

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function silentMode(): void
    {
        $this->silentContainer();

        $argument = $this->argument( 'type' );
        if ( is_array( $argument ) ) {
            $argument = array_map( static fn( string $string ) => strtolower( $string ), $argument );

            $this->type = implode( ' ', $argument );
        }
        else {
            $this->type = strtolower( $argument );
        }

        if ( !in_array( $this->type, $this->migrationTypes ) ) {
            $this->error( 'Тип миграции должен быть "create" или "update"' );

            exit();
        }

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Data" );
        FilesystemHelper::createDir( "app/Containers/$this->container/Data/Migrations" );

        if ( $this->type === 'create' ) {
            $stubFileName = 'migration.create';
        }
        else {
            $stubFileName = 'migration.update';
        }

        $this->argument = 'migration';
        $this->namespace = "App\Containers\\$this->container\Data\Migrations";
        $this->stubFileName = $stubFileName;

        $this->createFile();
        $this->info( 'Миграция создана!' );
    }
}
