<?php

namespace App\Base\Commands;

use App\Abstractions\Commands\ConsoleCommand;
use App\Abstractions\Database\Models\Model;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends ConsoleCommand
{
    use ConfirmableTrait;

    protected $name = 'db:seed';

    protected $description = 'Seed the database with records';

    protected Resolver $resolver;

    public function __construct( Resolver $resolver )
    {
        parent::__construct();

        $this->resolver = $resolver;
    }

    /**
     * @throws BindingResolutionException
     */
    public function handle(): int
    {
        if ( !$this->confirmToProceed() ) {
            return 1;
        }

        $previousConnection = $this->resolver->getDefaultConnection();

        $this->resolver->setDefaultConnection( $this->getDatabase() );

        Model::unguarded( function () {
            $this->getSeeder()->__invoke();
        } );

        if ( $previousConnection ) {
            $this->resolver->setDefaultConnection( $previousConnection );
        }

        $this->info( 'Database seeding completed successfully.' );

        return 0;
    }

    /**
     * @throws BindingResolutionException
     */
    protected function getSeeder(): Seeder
    {
        $class = $this->input->getArgument( 'class' ) ?? $this->input->getOption( 'class' );

        if ( $class === 'Database\\Seeders\\DatabaseSeeder' ) {
            $seederPath = class_exists( $class ) ? $class : 'DatabaseSeeder';
        }
        else {
            $class .= 'Seeder';

            $seederPath = '';
            if ( !str_contains( $class, '\\' ) ) {
                $filesystem = new Filesystem();
                foreach ( $filesystem->directories( app_path( 'Containers' ) ) as $container ) {
                    $containerPath = explode( '/', str_replace( '\\', '/', $container ) );
                    $containerName = array_pop( $containerPath );

                    $seederDir = "$container/Data/Seeders";
                    if ( $filesystem->exists( $seederDir ) ) {
                        foreach ( $filesystem->files( $seederDir ) as $seederFile ) {
                            $seederFileName = str_replace( '.php', '', $seederFile->getFilename() );
                            if ( $class === $seederFileName ) {
                                $seederPath = '\App\Containers\\' . $containerName . '\Data\Seeders\\' . $seederFileName;
                                break;
                            }
                        }
                    }
                }

                if ( empty( $seederPath ) ) {
                    $seederPath = 'Database\\Seeders\\' . $class;
                }
            }
        }

        return $this->laravel->make( $seederPath ?: $class )
            ->setContainer( $this->laravel )
            ->setCommand( $this );
    }

    protected function getDatabase(): string
    {
        $database = $this->input->getOption( 'database' );

        return $database ?: $this->laravel[ 'config' ][ 'database.default' ];
    }

    protected function getArguments(): array
    {
        return [
            [ 'class', InputArgument::OPTIONAL, 'The class name of the root seeder', null ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            [ 'class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', DatabaseSeeder::class ],
            [ 'database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed' ],
            [ 'force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production' ],
        ];
    }
}
