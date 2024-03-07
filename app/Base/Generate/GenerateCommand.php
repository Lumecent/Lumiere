<?php

namespace App\Base\Generate;

use App\Abstractions\Collections\Collection;
use App\Abstractions\Commands\ConsoleCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateCommand extends ConsoleCommand
{
    private string $stubPath = 'app/Base/Generate/stubs';

    protected ?string $argument = null;
    protected ?string $container = null;
    protected ?string $namespace = null;
    protected ?string $stubFileName = null;

    protected array $replaces = [];

    public function handle(): void
    {
        if ( $this->argument( 'mode' ) === 'interactive' ) {
            $this->interactiveMode();
        }
        else {
            $this->silentMode();
        }
    }

    /**
     * Предоставляет на выбор один из существующих контейнеров
     */
    public function interactiveContainer(): void
    {
        $containersCollection = new Collection();

        $directories = FilesystemHelper::getDirectories( 'app/Containers' );
        foreach ( $directories as $directory ) {
            $containerRaw = explode( '/', $directory );

            $containersCollection->push( array_pop( $containerRaw ) );
        }

        $this->container = $this->choice( 'Выберите контейнер', $containersCollection->toArray() );
    }

    /**
     * Получает контейнер из параметров команды
     */
    public function silentContainer(): void
    {
        $this->container = ucfirst( ( $this->argument( 'container' ) ) );
        if ( !$this->container ) {
            $this->error( "Вы не указали название контейнера!" );

            exit();
        }

        $this->checkContainer();
    }

    /**
     * Проверяет наличие контейнера в приложении
     */
    public function checkContainer(): void
    {
        if ( !FilesystemHelper::existsDir( 'app/Containers/' . $this->container ) ) {
            $this->error( "Контейнер '$this->container' не найден" );

            exit();
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function createFile( string $classPostfix = '' ): void
    {
        if ( $this->argument ) {
            $className = ucfirst( $this->argument( $this->argument ) ) . ucfirst( $classPostfix );
        }
        else {
            $classPath = explode( '\\', $this->namespace );
            $className = array_pop( $classPath ) . ucfirst( $classPostfix );

            $this->namespace = implode( '\\', $classPath );
        }

        $className = $this->createSubDirectories( $className );

        $fileName = "$this->namespace\\$className";
        if ( class_exists( $fileName ) ) {
            $this->error( $fileName . ' уже существует!' );

            exit();
        }

        $this->replaces = [
            '{{ class }}' => $className,
            '{{ namespace }}' => $this->namespace
        ];

        $contentNewFile = $this->parseStubFile();

        FilesystemHelper::createFile( str_replace( '\\', '/', lcfirst( $fileName ) ) . ".php", $contentNewFile );
    }

    /**
     * Мод, в котором пользователь последовательно вводит или выбирает контейнеры, типы контроллеров и пр.
     * @return void
     */
    protected function interactiveMode(): void
    {
        $this->interactiveContainer();

        $this->processGenerateFile();
    }

    /**
     * Мод, в котором вся необходимая информация прописана в строке команды
     * @return void
     */
    protected function silentMode(): void
    {
        $this->silentContainer();

        $this->processGenerateFile();
    }

    /**
     * Основная логика генерации файлов
     * @return void
     */
    protected function processGenerateFile(): void
    {
    }

    protected function createSubDirectories( $className ): string
    {
        if ( str_contains( $className, '/' ) ) {
            $directories = explode( '/', $className );

            $className = array_pop( $directories );

            foreach ( $directories as $directory ) {
                $this->namespace .= '\\' . $directory;

                $path = str_replace( '\\', '/', $this->namespace );

                FilesystemHelper::createDir( $path );
            }
        }

        return $className;
    }

    /**
     * @throws FileNotFoundException
     */
    protected function parseStubFile(): string
    {
        $search = array_keys( $this->replaces );
        $replace = array_values( $this->replaces );

        $content = FilesystemHelper::getContentFile( "$this->stubPath/$this->stubFileName.stub" );

        return str_replace( $search, $replace, $content );
    }
}
