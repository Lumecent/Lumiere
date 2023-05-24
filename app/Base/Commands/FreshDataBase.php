<?php

namespace App\Base\Commands;

use App\Abstractions\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Artisan;

class FreshDataBase extends ConsoleCommand
{
    protected $signature = 'lumiere:db_fresh';

    protected $description = 'Fresh database';

    public function handle()
    {
        $this->info( 'Refresh migrations' );

        Artisan::call( 'migrate:refresh' );

        $this->info( 'Seeding database' );

        Artisan::call( 'db:seed' );
    }
}
