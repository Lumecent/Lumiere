<?php

namespace App\Base\Generate\Commands;

use App\Abstractions\Collections\ModelsCollection;
use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateFactory extends GenerateCommand
{
    protected $signature = 'lumiere:factory {factory} {mode=interactive} {container?} {model?}';

    protected $description = 'Создаёт фабрику для генерации моделей';

    private ?string $model = null;
    private ?string $modelNamespace = null;

    public function choiceModel( $containerPath ): string
    {
        $modelsCollection = new ModelsCollection();

        $models = FilesystemHelper::getFiles( $containerPath );
        foreach ( $models as $model ) {
            $modelNameRaw = explode( '/', $model );

            $modelsCollection->push( str_replace( '.php', '', array_pop( $modelNameRaw ) ) );
        }

        return $this->choice( 'Выберите модель', $modelsCollection->toArray() );
    }

    public function createFile( $classPostfix = '' ): void
    {
        $className = ucfirst( $this->argument( $this->argument ) ) . $classPostfix;

        $modelNamespaceRaw = explode( '\\', $this->modelNamespace );
        $model = array_pop( $modelNamespaceRaw );

        $fileName = "$this->namespace\\$className";
        if ( class_exists( $fileName ) ) {
            $this->error( $fileName . ' уже существует!' );

            exit();
        }

        $this->replaces = [
            '{{ class }}' => $className,
            '{{ model }}' => $model,
            '{{ factoryNamespace }}' => $this->namespace,
            '{{ modelNamespace }}' => ucfirst( $this->modelNamespace )
        ];

        $contentNewFile = $this->parseStubFile();

        FilesystemHelper::createFile( lcfirst( str_replace( '\\', '/', $fileName ) ) . ".php", $contentNewFile );
    }

    /**
     * @throws FileNotFoundException
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->model = $this->choiceModel( "app/Containers/$this->container/Models" );

        $modelPath = "app/Containers/$this->container/Models/$this->model.php";

        $this->checkModel( $modelPath );

        $this->modelNamespace = str_replace( [ '.php', '/' ], [ '', '\\' ], $modelPath );

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function silentMode(): void
    {
        $this->silentContainer();

        $this->model = ucfirst( ( $this->argument( 'model' ) ) );
        if ( !$this->model ) {
            $this->error( "Введите название модели!" );

            exit();
        }

        $modelPath = "app/Containers/$this->container/Models/$this->model.php";

        $this->checkModel( $modelPath );

        $this->modelNamespace = str_replace( [ '.php', '/' ], [ '', '\\' ], $modelPath );

        $this->processGenerateFile();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(): void
    {
        FilesystemHelper::createDir( "app/Containers/$this->container/Data/Factories" );

        $this->argument = 'factory';
        $this->namespace = "App\Containers\\$this->container\Data\Factories";
        $this->stubFileName = 'factory';

        $this->createFile( 'Factory' );

        $this->info( 'Фабрика создана!' );
    }

    private function checkModel( $modelPath ): void
    {
        if ( !FilesystemHelper::existsFile( $modelPath ) ) {
            $this->error( "Модель '$modelPath' не найдена" );

            exit();
        }
    }
}