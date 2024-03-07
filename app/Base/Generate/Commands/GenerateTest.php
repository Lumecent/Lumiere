<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateTest extends GenerateCommand
{
    protected $signature = 'lumiere:tests {test} {mode=interactive} {container?} {type?} {directory?} {version?}';

    protected $description = 'Создаёт новый контроллер в контейнере';

    private ?string $type = null;
    private ?string $directory = null;
    private ?string $version = null;

    private array $featureTests = [
        'Api'
    ];
    private array $unitTests = [
        'Events',
        'Listeners',
        'Models',
        'Observers',
        'Repositories',
        'Services',
    ];

    private array $typeTests = [
        'Feature', 'Unit'
    ];

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->type = ucfirst( ( $this->choice( 'Выберите тип тестов', $this->typeTests ) ) );

        if ( $this->type === 'Feature' ) {
            $this->directory = $this->choice( 'Выберите раздел тестирования', $this->featureTests );
            if ( $this->directory === 'Api' ) {
                $this->version = $this->choice( 'Выберите версию API', config( 'lumiere.api_versions' ) );
            }
        }
        else {
            $this->directory = $this->choice( 'Выберите раздел тестирования', $this->unitTests );
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
        if ( !in_array( $this->type, $this->typeTests ) ) {
            $this->error( 'Доступные типы тестов: ' . implode( ', ', $this->typeTests ) );

            exit();
        }

        $this->directory = ucfirst( strtolower( $this->argument( 'directory' ) ) );

        if ( $this->type === 'Unit' ) {
            if ( !in_array( $this->directory, $this->unitTests ) ) {
                $this->error( 'Доступные разделы тестирования: ' . implode( ', ', $this->unitTests ) );

                exit();
            }
        }

        if ( $this->type === 'Feature' ) {
            if ( !in_array( $this->directory, $this->featureTests ) ) {
                $this->error( 'Доступные разделы тестирования: ' . implode( ', ', $this->featureTests ) );

                exit();
            }

            if ( $this->directory === 'Api' ) {
                $this->version = $this->argument( 'version' );
                if ( !in_array( $this->type, config( 'lumiere.api_versions' ) ) ) {
                    $this->error( 'Доступные версии API: ' . implode( ', ', config( 'lumiere.api_versions' ) ) );

                    exit();
                }
            }
        }

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Tests" );
        FilesystemHelper::createDir( "app/Containers/$this->container/Tests/$this->type" );
        FilesystemHelper::createDir( "app/Containers/$this->container/Tests/$this->type/$this->directory" );

        if ( $this->version ) {
            FilesystemHelper::createDir( "app/Containers/$this->container/Tests/$this->type/$this->directory/V$this->version" );
        }

        $this->argument = 'test';
        $this->namespace = "App\Containers\\$this->container\Tests\\$this->type\\$this->directory" . ( $this->version ? "\\V$this->version" : '' );
        $this->stubFileName = 'test';

        $this->createFile( 'test' );

        $this->info( 'Тест создан!' );
    }
}
