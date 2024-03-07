<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateObserver extends GenerateCommand
{
    protected $signature = 'lumiere:observer {observer} {mode=interactive} {container?} {--model=}';

    protected $description = 'Создаёт нового наблюдателя';

    private ?string $modelNamespace = null;
    private ?string $modelVariable = null;
    private ?string $model = null;

    public function createFile( string $classPostfix = '' ): void
    {
        $className = ucfirst( $this->argument( $this->argument ) ) . ucfirst( $classPostfix );

        $className = $this->createSubDirectories( $className );

        $fileName = "$this->namespace\\$className";
        if ( class_exists( $fileName ) ) {
            $this->error( $fileName . ' already exists!' );

            exit();
        }

        $this->replaces = [
            '{{ class }}' => $className,
            '{{ namespace }}' => $this->namespace,
            '{{ namespacedModel }}' => $this->modelNamespace,
            '{{ model }}' => $this->model,
            '{{ modelVariable }}' => $this->modelVariable
        ];

        $contentNewFile = $this->parseStubFile();

        FilesystemHelper::createFile( str_replace( '\\', '/', lcfirst( $fileName ) ) . ".php", $contentNewFile );
    }

    /**
     * @throws FileNotFoundException
     */
    protected function processGenerateFile(  ): void
    {
        if ( $model = $this->option( 'model' ) ) {
            $model = ucfirst( $model );

            if ( !FilesystemHelper::existsFile( "app/Containers/$this->container/Models/$model.php" ) ) {
                $this->error( "Модель app/Containers/$this->container/Models/$model не найдена!" );

                exit();
            }
        }

        FilesystemHelper::createDir( "app/Containers/$this->container/Observers" );

        $this->argument = 'observer';
        $this->namespace = "App\Containers\\$this->container\Observers";
        $this->modelNamespace = $model ? "App\Containers\\$this->container\Models\\$model" : null;
        $this->modelVariable = $model ? lcfirst( $model ) : null;
        $this->model = $model ?? null;
        $this->stubFileName = $model ? 'observer' : 'observer.plain';

        $this->createFile( 'Observer' );

        $this->info( 'Наблюдатель создан!' );
    }
}