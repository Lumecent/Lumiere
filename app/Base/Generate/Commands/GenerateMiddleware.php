<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateMiddleware extends GenerateCommand
{
    protected $signature = 'lumiere:middleware {middleware}';

    protected $description = 'Создаёт новое промежуточное ПО';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->argument = 'middleware';
        $this->namespace = "App\Base\Middlewares";
        $this->stubFileName = 'middleware';

        $this->createFile( 'Middleware' );

        $this->info( 'ПО создано!' );
    }
}
