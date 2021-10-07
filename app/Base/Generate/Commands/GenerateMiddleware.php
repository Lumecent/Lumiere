<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;

class GenerateMiddleware extends GenerateCommand
{
    protected $signature = 'lumiere:middleware {middleware}';

    protected $description = 'Create a new middleware';

    public function handle(): void
    {
        $this->createFile( [ 'middleware', 'App\Base\Middleware' ], 'middleware', 'Middleware' );
        $this->info( 'Middleware created!' );
    }
}
