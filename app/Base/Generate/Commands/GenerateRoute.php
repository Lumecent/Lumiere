<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateRoute extends GenerateCommand
{
    protected $signature = 'lumiere:route {route} {mode=interactive} {container?} {type?} {version?}';

    protected $description = 'Создаёт новый маршрут';

    private ?string $type = null;
    private ?string $version = null;

    private array $typeRoutes = [
        'Api', 'Web'
    ];

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->type = ucfirst( strtolower( $this->choice( 'Выберите тип маршрута', $this->typeRoutes ) ) );

        if ( $this->type === 'Api' ) {
            $this->version = $this->choice( 'Выберите версию API', config( 'lumiere.api_versions' ) );
        }

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function silentMode(): void
    {
        $this->silentContainer();

        $this->type = ucfirst( strtolower( $this->argument( 'type' ) ) );
        if ( !in_array( $this->type, $this->typeRoutes ) ) {
            $this->error( 'Доступные типы маршрутов: ' . implode( ', ', $this->typeRoutes ) );

            exit();
        }

        if ( $this->type === 'Api' ) {
            $this->version = $this->argument( 'version' );
            if ( !in_array( $this->type, config( 'lumiere.api_versions' ) ) ) {
                $this->error( 'Доступные версии API: ' . implode( ', ', config( 'lumiere.api_versions' ) ) );
            }
        }

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Routes" );
        FilesystemHelper::createDir( "app/Containers/$this->container/Routes/$this->type" );

        if ( $this->version ) {
            FilesystemHelper::createDir( "app/Containers/$this->container/Routes/$this->type/V$this->version" );
        }

        $this->argument = 'route';
        $this->namespace = "App\Containers\\$this->container\Routes\\$this->type" . ( $this->version ? "\\V$this->version" : '' );
        $this->stubFileName = 'route.' . strtolower( $this->type );

        $this->createFile( 'Route' );

        $this->info( 'Маршрут создан!' );
    }
}
