<?php

namespace App\Base\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;

class RouteServiceProvider extends ServiceProvider
{
    public const string HOME = '/home';

    public function boot(): void
    {
        parent::boot();

        $isProduction = config( 'app.env' ) === 'production';
        $uiRouteProviderList = $this->getRouteList( $isProduction );

        foreach ( $uiRouteProviderList as $uiRouteProvider ) {
            $this->app->register( $uiRouteProvider );
        }

        $this->app[ 'router' ]->middleware( 'web' );
    }

    protected function getRouteList( $isProduction )
    {
        $routeProviderListFunction = static function () {
            $routeProviderList = [];
            $filesystem = new Filesystem();

            foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $container ) {
                $containerPath = explode( '/', str_replace( '\\', '/', $container ) );
                $containerName = array_pop( $containerPath );

                if ( $filesystem->exists( $container . '/Routes/Web' ) ) {
                    foreach ( $filesystem->files( $container . '/Routes/Web' ) as $routeFile ) {
                        $routeProviderList[] = '\App\Containers\\' . $containerName . '\Routes\Web\\' . str_replace( '.php', '', $routeFile->getFilename() );
                    }
                }

                if ( $filesystem->exists( $container . '/Routes/Api' ) ) {
                    foreach ( $filesystem->directories( $container . '/Routes/Api' ) as $versionRoute ) {
                        $versionRaw = explode( '/', $versionRoute );
                        $version = array_pop( $versionRaw );

                        foreach ( $filesystem->files( $versionRoute ) as $routeFile ) {
                            $routeProviderList[] = "\App\Containers\\$containerName\Routes\Api\\$version\\" . str_replace( '.php', '', $routeFile->getFilename() );
                        }
                    }
                }
            }

            return $routeProviderList;
        };

        if ( $isProduction ) {
            $routeProviderList = Cache::get( 'routeProviderList', function () use ( $routeProviderListFunction ) {
                $routeProviderList = $routeProviderListFunction();
                Cache::put( 'routeProviderList', $routeProviderList, 60 );
                return $routeProviderList;
            } );
        }
        else {
            $routeProviderList = $routeProviderListFunction();
        }

        return $routeProviderList;
    }
}
