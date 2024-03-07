<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateController extends GenerateCommand
{
    protected $signature = 'lumiere:controller {controller} {mode=interactive} {container?} {type?} {version?}';

    protected $description = 'Создаёт новый контроллер в контейнере';

    private ?string $type = null;
    private ?string $version = null;

    private array $typeControllers = [
        'Api', 'Web', 'Console'
    ];

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->type = ucfirst( ( $this->choice( 'Выберите тип контроллера', $this->typeControllers ) ) );

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
        if ( !in_array( $this->type, $this->typeControllers ) ) {
            $this->error( 'Доступные типы контроллеров: ' . implode( ', ', $this->typeControllers ) );

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
        FilesystemHelper::createDir( "app/Containers/$this->container/Controllers" );
        FilesystemHelper::createDir( "app/Containers/$this->container/Controllers/$this->type" );

        if ( $this->version ) {
            FilesystemHelper::createDir( "app/Containers/$this->container/Controllers/$this->type/V$this->version" );
        }

        $this->argument = 'controller';
        $this->namespace = "App\Containers\\$this->container\Controllers\\$this->type" . ( $this->version ? "\\V$this->version" : '');
        $this->stubFileName = 'controller.' . strtolower( $this->type );

        $this->createFile( 'Controller' );

        $this->info( 'Контроллер создан!' );
    }
}
