<?php

namespace App\Utilities\Facades;

use App\Abstractions\Facades\Facade;
use App\Containers\Artist\Filters\ArtistFilter;
use App\Containers\Deal\Filters\DealFilter;
use App\Containers\Item\Filters\ItemFilter;
use App\Containers\Report\Filters\ReportFilter;
use App\Containers\Ticket\Filters\TicketFilter;
use App\Containers\Transaction\Filters\TransactionFilter;

/**
 * @method static ArtistFilter artist()
 * @method static DealFilter deal()
 * @method static ItemFilter item()
 * @method static ReportFilter report()
 * @method static TicketFilter ticket()
 * @method static TransactionFilter transaction()
 */
class Filter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Filter';
    }
}