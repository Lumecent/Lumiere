<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateContainer extends GenerateCommand
{
    protected $signature = 'lumiere:container {container}';

    protected $description = 'Создаёт новый контейнер';

    public function handle(): void
    {
        $this->interactiveMode();
    }

    protected function interactiveMode(): void
    {
        $container = ucfirst( $this->argument( 'container' ) );
        if ( !FilesystemHelper::existsDir( $container ) ) {
            FilesystemHelper::createDir( 'app/Containers/' . $container );
        }

        if ( $this->confirm( 'Создать контроллер?', true ) ) {
            $type = ucfirst( strtolower( $this->choice( 'Выберите тип контроллера', [ 'Api', 'Web', 'Console' ] ) ) );
            $version = $type === 'Api' ? $this->choice( 'Выберите версию API', config( 'lumiere.api_versions' ) ) : '';

            Artisan::call( "lumiere:controller $container silent $container $type $version" );
        }

        if ( $this->confirm( 'Создать модель?', true ) ) {
            Artisan::call( "lumiere:model $container silent $container" );

            $this->info( 'Модель создана!' );
        }

        if ( $this->confirm( 'Создать миграцию?', true ) ) {
            $type = 'create';

            Artisan::call( "lumiere:migration $container silent $container $type" );

            $this->info( 'Миграция создана!' );
        }

        if ( $this->confirm( 'Создать DTO?', true ) ) {
            Artisan::call( "lumiere:dto $container silent $container" );
        }

        if ( $this->confirm( 'Создать репозиторий?', true ) ) {
            Artisan::call( "lumiere:repository $container silent $container" );
        }

        if ( $this->confirm( 'Создать интерфейс?', true ) ) {
            Artisan::call( "lumiere:interface $container silent $container" );
        }

        if ( $this->confirm( 'Создать провайдер?', true ) ) {
            Artisan::call( "lumiere:provider $container silent $container" );
        }

        if ( $this->confirm( 'Создать маршрут?', true ) ) {
            $type = ucfirst( strtolower( $this->choice( 'Выберите тип маршрута', [ 'Api', 'Web' ] ) ) );
            $version = $type === 'Api' ? $this->choice( 'Выберите версию API', config( 'lumiere.api_versions' ) ) : '';

            Artisan::call( "lumiere:route $container silent $container $type $version" );
        }

        $this->info( 'Контейнер создан!' );
    }
}
