<?php

namespace App\Abstractions\Database\Builders;

use Abstractions\Database\Paginator\LengthAwarePaginator;
use App\Abstractions\Collections\ModelsCollection;
use App\Abstractions\Database\Models\Model;
use App\Abstractions\Database\Paginator\CursorPaginator;
use App\Abstractions\Interfaces\ArrayableInterface;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder as IlluminateBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Builder extends IlluminateBuilder
{
    /** @var Model */
    protected $model;

    public function getModel(): self|Model
    {
        return $this->model;
    }

    public function find( $id, $columns = [ '*' ] ): Model|ModelsCollection|static|null
    {
        if ( is_array( $id ) || $id instanceof ArrayableInterface ) {
            return $this->findMany( $id, $columns );
        }

        return $this->whereKey( $id )->first( $columns );
    }

    public function findMany( $ids, $columns = [ '*' ] ): ModelsCollection
    {
        $ids = $ids instanceof ArrayableInterface ? $ids->toArray() : $ids;

        if ( empty( $ids ) ) {
            return $this->model->newCollection();
        }

        return $this->whereKey( $ids )->get( $columns );
    }

    /**
     * @throws Throwable
     */
    public function findOrFail( $id, $columns = [ '*' ] ): Model|ModelsCollection|static|array
    {
        $result = $this->find( $id, $columns );

        $id = $id instanceof ArrayableInterface ? $id->toArray() : $id;

        if ( is_array( $id ) ) {
            if ( count( $result ) !== count( array_unique( $id ) ) ) {
                throw ( new ModelNotFoundException )->setModel(
                    get_class( $this->model ), array_diff( $id, $result->modelKeys() )
                );
            }

            return $result;
        }

        if ( is_null( $result ) ) {
            throw ( new ModelNotFoundException )->setModel(
                get_class( $this->model ), $id
            );
        }

        return $result;
    }

    public function first( $columns = [ '*' ] ): Model|static|null
    {
        return $this->take( 1 )->get( $columns )->first();
    }

    public function get( $columns = [ '*' ] ): ModelsCollection
    {
        $builder = $this->applyScopes();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded, which will solve the
        // n+1 query issue for the developers to avoid running a lot of queries.
        if ( count( $models = $builder->getModels( $columns ) ) > 0 ) {
            $models = $builder->eagerLoadRelations( $models );
        }

        return $builder->getModel()->newCollection( $models );
    }

    public function setEagerLoads( array $eagerLoad ): Builder
    {
        $this->eagerLoad = $eagerLoad;

        return $this;
    }

    public function withGlobalScope( $identifier, $scope ): Builder
    {
        $this->scopes[ $identifier ] = $scope;

        if ( method_exists( $scope, 'extend' ) ) {
            $scope->extend( $this );
        }

        return $this;
    }

    public function cursorPaginate( $perPage = null, $columns = [ '*' ], $cursorName = 'cursor', $cursor = null ): CursorPaginator
    {
        $perPage = $perPage ?: $this->model->getPerPage();

        return $this->paginateUsingCursor( $perPage, $columns, $cursorName, $cursor );
    }

    protected function paginateUsingCursor( $perPage, $columns = [ '*' ], $cursorName = 'cursor', $cursor = null ): CursorPaginator
    {
        /** @var CursorPaginator $paginator */
        $paginator = parent::paginateUsingCursor( $perPage, $columns, $cursorName, $cursor );

        return $paginator;
    }

    /**
     * @throws BindingResolutionException
     */
    protected function cursorPaginator( $items, $perPage, $cursor, $options ): CursorPaginator
    {
        return Container::getInstance()->makeWith( CursorPaginator::class, compact(
            'items', 'perPage', 'cursor', 'options'
        ) );
    }

    public function paginate( $perPage = null, $columns = [ '*' ], $pageName = 'page', $page = null ): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = parent::paginate( $perPage, $columns, $pageName, $page );

        return $paginator;
    }

    /**
     * @throws BindingResolutionException
     */
    protected function paginator( $items, $total, $perPage, $currentPage, $options )
    {
        return Container::getInstance()->makeWith( LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ) );
    }
}