<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Utilities\Helpers\FilesystemHelper;
use Illuminate\Support\Facades\Artisan;

class GenerateContainer extends GenerateCommand
{
    protected $signature = 'lumiere:container {container}';

    protected $description = 'Create a new containers';

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

        if ( $this->confirm( 'Generate Controller?', true ) ) {
            $type = ucfirst( strtolower( $this->choice( 'Specify the controller type', [ 'Api', 'Web', 'Console' ] ) ) );

            Artisan::call( "lumiere:controller $container silent $container $type" );
        }

        if ( $this->confirm( 'Generate Model?', true ) ) {
            Artisan::call( "lumiere:model $container silent $container" );

            $this->info( 'Model created!' );
        }

        if ( $this->confirm( 'Generate Migration?', true ) ) {
            $type = 'create table';

            Artisan::call( "lumiere:migration $container silent $container $type" );

            $this->info( 'Migration created!' );
        }

        if ( $this->confirm( 'Generate DTO?', true ) ) {
            Artisan::call( "lumiere:dto $container silent $container" );
        }

        if ( $this->confirm( 'Generate Repository?', true ) ) {
            Artisan::call( "lumiere:repository $container silent $container" );
        }

        if ( $this->confirm( 'Generate Interface?', true ) ) {
            Artisan::call( "lumiere:interface $container silent $container" );
        }

        if ( $this->confirm( 'Generate Service Provider?', true ) ) {
            Artisan::call( "lumiere:provider $container silent $container" );
        }

        if ( $this->confirm( 'Generate Route?', true ) ) {
            $type = ucfirst( strtolower( $this->choice( 'Specify the route type', [ 'Api', 'Web' ] ) ) );

            Artisan::call( "lumiere:route $container silent $container $type" );
        }

        $this->info( 'Container created!' );
    }
}
