<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use App\Base\Helpers\FilesystemHelper;

class GenerateRule extends GenerateCommand
{
    protected $signature = 'lumiere:rule {rule}';

    protected $description = 'Create a new rules';

    public function handle(): void
    {
        $container = ucfirst( strtolower( $this->ask( 'Specify the container name' ) ) );
        $this->checkContainer( $container );

        FilesystemHelper::createDir( "app/Containers/$container/Rules" );

        $this->createFile( 'rule', "App\Containers\\$container\Rules", 'rule' );
        $this->info( 'Rule created!' );
    }
}
