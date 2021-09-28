<?php

namespace App\Base\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
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
                    foreach ( $filesystem->files( $container . '/Routes/Api' ) as $routeFile ) {
                        $routeProviderList[] = '\App\Containers\\' . $containerName . '\Routes\Api\\' . str_replace( '.php', '', $routeFile->getFilename() );
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
        } else {
            $routeProviderList = $routeProviderListFunction();
        }

        return $routeProviderList;
    }
}
