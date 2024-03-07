<?php

namespace App\Utilities\Enums;

enum FilterDateEnum: string
{
    case PERIOD_CURRENT_WEEK = 'current_week';
    case PERIOD_PREV_WEEK = 'prev_week';
    case PERIOD_CURRENT_MONTH = 'current_month';
    case PERIOD_PREV_MONTH = 'prev_month';
    case PERIOD_QUARTER = 'quarter';
    case PERIOD_SIX_MONTH = 'six_months';
    case PERIOD_YEAR = 'year';
    case PERIOD_CUSTOM = 'custom';
}
