<?php

namespace App\Abstractions\Database\Filters;

use App\Abstractions\Database\Builders\Builder;
use App\Abstractions\Facades\Arr;
use App\Abstractions\Facades\Carbon;
use App\Utilities\Enums\FilterDateEnum;

abstract class Filter
{
    public function name( array $filters, Builder $builder ): self
    {
        if ( $this->hasFilter( $filters, 'name' ) ) {
            $name = $this->getFilter( $filters, 'name' );

            $builder->where( 'name', 'like', '%' . $name . '%' );
        }

        return $this;
    }

    public function price( array $filters, Builder $builder ): self
    {
        $from = $this->getFilter( $filters, 'price_from' );
        $to = $this->getFilter( $filters, 'price_to' );

        if ( $from ) {
            $builder->where( 'price', '>=', $from * 100 );
        }

        if ( $to ) {
            $builder->where( 'price', '<=', $to * 100 );
        }

        return $this;
    }

    public function period( array $filters, Builder $builder ): self
    {
        $periodType = $this->getFilter( $filters, 'period' );

        switch ( $periodType ) {
            case FilterDateEnum::PERIOD_CURRENT_WEEK->value:
                $builder->whereDate( 'created_at', '>', Carbon::now()->subDays( 7 ) );
                break;
            case FilterDateEnum::PERIOD_PREV_WEEK->value:
                $builder
                    ->whereDate( 'created_at', '<=', Carbon::now()->subDays( 7 ) )
                    ->whereDate( 'created_at', '>', Carbon::now()->subDays( 14 ) );
                break;
            case FilterDateEnum::PERIOD_CURRENT_MONTH->value:
                $builder->whereDate( 'created_at', '>', Carbon::now()->subDays( 30 ) );
                break;
            case FilterDateEnum::PERIOD_PREV_MONTH->value:
                $builder
                    ->whereDate( 'created_at', '<=', Carbon::now()->subDays( 30 ) )
                    ->whereDate( 'created_at', '>', Carbon::now()->subDays( 60 ) );
                break;
            case FilterDateEnum::PERIOD_QUARTER->value:
                $builder->whereDate( 'created_at', '>', Carbon::now()->subDays( 90 ) );
                break;
            case FilterDateEnum::PERIOD_SIX_MONTH->value:
                $builder->whereDate( 'created_at', '>', Carbon::now()->subDays( 180 ) );
                break;
            case FilterDateEnum::PERIOD_YEAR->value:
                $builder->whereDate( 'created_at', '>', Carbon::now()->subDays( 360 ) );
                break;
            case FilterDateEnum::PERIOD_CUSTOM->value:
                $builder
                    ->whereDate( 'created_at', '>=', Carbon::parse( Arr::get( $filters, 'date_from' ) ) )
                    ->whereDate( 'created_at', '<=', Carbon::parse( Arr::get( $filters, 'date_to' ) ) );
                break;
        }

        return $this;
    }

    public function status( array $filters, Builder $builder ): self
    {
        if ( $this->hasFilter( $filters, 'status' ) && $this->notAllValue( $filters, 'status' ) ) {
            $builder->where( 'status', $this->getFilter( $filters, 'status' ) );
        }

        return $this;
    }

    public function active( array $filters, Builder $builder ): self
    {
        if ( $this->hasFilter( $filters, 'active' ) && $this->notAllValue( $filters, 'active' ) ) {
            $builder->where( 'is_active', $this->getFilter( $filters, 'active' ) === 'active' );
        }

        return $this;
    }

    public function moderator( array $filters, Builder $builder ): self
    {
        if ( $this->hasFilter( $filters, 'moderator' ) && $this->notAllValue( $filters, 'moderator' ) ) {
            $builder->where( 'moderator_id', $this->getFilter( $filters, 'moderator' ) );
        }

        return $this;
    }

    protected function hasFilter( array $filters, string $filter ): bool
    {
        return Arr::has( $filters, $filter );
    }

    protected function getFilter( array $filters, string $filter ): mixed
    {
        return Arr::get( $filters, $filter );
    }

    protected function notAllValue( array $filters, string $filter ): bool
    {
        return Arr::get( $filters, $filter ) !== 'all';
    }
}