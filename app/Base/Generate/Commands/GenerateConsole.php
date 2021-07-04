<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;

class GenerateConsole extends GenerateCommand
{
    protected $signature = 'lumiere:command {console}';

    protected $description = 'Create a new command';

    public function handle(): void
    {
        $this->createFile( 'console', 'App\Base\Commands', 'console' );
        $this->info( 'Command created!' );
    }
}
