<?php

namespace App\Abstractions\Models;

use App\Abstractions\Collections\Collection;
use Illuminate\Database\Eloquent\Model as IlluminateModel;

abstract class Model extends IlluminateModel
{
    /**
     * Get all the models from the database.
     *
     * @param array|mixed $columns
     * @return Collection|static[]
     */
    public static function all( $columns = [ '*' ] ): Collection|array
    {
        return static::query()->get(
            is_array( $columns ) ? $columns : func_get_args()
        );
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array|string $columns
     * @return Collection|static
     */
    public function get( array|string $columns = [ '*' ] ): Collection|static
    {
        $builder = $this->applyScopes();

        if ( count( $models = $builder->getModels( $columns ) ) > 0 ) {
            $models = $builder->eagerLoadRelations( $models );
        }

        return $builder->getModel()->newCollection( $models );
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     * @return Collection
     */
    public function newCollection( array $models = [] ): Collection
    {
        return new Collection( $models );
    }
}
