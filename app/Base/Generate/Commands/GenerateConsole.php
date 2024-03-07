<?php

namespace App\Base\Generate\Commands;

use App\Base\Generate\GenerateCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GenerateConsole extends GenerateCommand
{
    protected $signature = 'lumiere:command {console}';

    protected $description = 'Создаёт новую консольную команду';

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->argument = 'console';
        $this->namespace = 'App\Base\Commands';
        $this->stubFileName = 'console';

        $this->createFile( 'Command' );

        $this->info( 'Команда создана!' );
    }
}
