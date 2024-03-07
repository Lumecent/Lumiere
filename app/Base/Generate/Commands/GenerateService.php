<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateService extends GenerateCommand
{
    protected $signature = 'lumiere:service {services} {mode=interactive} {service?} {type?}';

    protected $description = 'Создаёт новый сервис';

    private ?string $type = null;

    private array $typeServices = [
        'factory',
        'service'
    ];

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->type = $this->choice( 'Выберите тип сервиса', $this->typeServices );

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function silentMode(): void
    {
        $this->silentContainer();

        $this->type = strtolower( $this->argument( 'type' ) );
        if ( !in_array( $this->type, $this->typeServices ) ) {
            $this->error( 'Доступные типы сервисов: ' . implode( ', ', $this->typeServices ) );

            exit();
        }

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Services" );

        $this->argument = 'services';
        $this->namespace = "App\Containers\\$this->container\Services";
        $this->stubFileName = 'service' . ( $this->type === 'factory' ? '.factory' : '' );

        $this->createFile( $this->type === 'factory' ? 'ServiceFactory' : 'Service' );

        $this->info( 'Сервис создан!' );
    }
}