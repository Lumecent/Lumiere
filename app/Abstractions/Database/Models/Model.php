<?php

namespace App\Abstractions\Database\Models;

use App\Abstractions\Collections\ModelsCollection;
use App\Abstractions\Database\Builders\Builder;
use App\Abstractions\Database\Relations\BelongsTo;
use App\Abstractions\Database\Relations\BelongsToMany;
use App\Abstractions\Database\Relations\HasMany;
use App\Abstractions\Database\Relations\HasManyThrough;
use App\Abstractions\Database\Relations\HasOne;
use App\Abstractions\Database\Relations\HasOneThrough;
use App\Abstractions\Database\Relations\MorphMany;
use App\Abstractions\Database\Relations\MorphOne;
use App\Abstractions\Database\Relations\MorphTo;
use App\Abstractions\Database\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Builder as IlluminateBuilder;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Support\Carbon;

abstract class Model extends IlluminateModel
{
    protected $guarded = [
        'id',
    ];

    public function getCreatedAtAttribute( $value ): string
    {
        return Carbon::createFromTimestamp( strtotime( $value ) )
            ->format( isset( $this->casts[ 'created_at' ] ) ? 'Y-m-d H:i:s' : 'd.m.y в H:i' );
    }

    public static function query(): Builder
    {
        return ( new static )->newQuery();
    }

    public function newQuery(): Builder
    {
        return $this->registerGlobalScopes( $this->newQueryWithoutScopes() );
    }

    public function newQueryWithoutScopes(): Builder|Model
    {
        return $this->newModelQuery()
            ->with( $this->with )
            ->withCount( $this->withCount );
    }

    public function registerGlobalScopes( $builder ): Builder
    {
        /** @var Builder $builder */

        foreach ( $this->getGlobalScopes() as $identifier => $scope ) {
            $builder->withGlobalScope( $identifier, $scope );
        }

        return $builder;
    }

    public function newEloquentBuilder( $query ): Builder
    {
        return new Builder( $query );
    }

    public static function all( $columns = [ '*' ] ): ModelsCollection|array
    {
        return static::query()->get(
            is_array( $columns ) ? $columns : func_get_args()
        );
    }

    public function get( array|string $columns = [ '*' ] ): ModelsCollection|static
    {
        $builder = $this->applyScopes();

        if ( count( $models = $builder->getModels( $columns ) ) > 0 ) {
            $models = $builder->eagerLoadRelations( $models );
        }

        return $builder->getModel()->newCollection( $models );
    }

    public function newCollection( array $models = [] ): ModelsCollection
    {
        return new ModelsCollection( $models );
    }

    public function hasOne( $related, $foreignKey = null, $localKey = null ): HasOne
    {
        /** @var HasOne $parent */
        $parent = parent::hasOne( $related, $foreignKey, $localKey );

        return $parent;
    }

    public function hasOneThrough( $related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null ): HasOneThrough
    {
        /** @var HasOneThrough $parent */
        $parent = parent::hasOneThrough( $related, $through, $firstKey, $secondKey, $localKey, $secondLocalKey );

        return $parent;
    }

    public function morphOne( $related, $name, $type = null, $id = null, $localKey = null ): MorphOne
    {
        /** @var MorphOne $parent */
        $parent = parent::morphOne( $related, $name, $type, $id, $localKey );

        return $parent;
    }

    public function belongsTo( $related, $foreignKey = null, $ownerKey = null, $relation = null ): BelongsTo
    {
        /** @var BelongsTo $parent */
        $parent = parent::belongsTo( $related, $foreignKey, $ownerKey, $relation );

        return $parent;
    }

    public function morphTo( $name = null, $type = null, $id = null, $ownerKey = null ): MorphTo
    {
        /** @var MorphTo $parent */
        $parent = parent::morphTo( $name, $type, $id, $ownerKey );

        return $parent;
    }

    public function hasMany( $related, $foreignKey = null, $localKey = null ): HasMany
    {
        /** @var HasMany $parent */
        $parent = parent::hasMany( $related, $foreignKey, $localKey );

        return $parent;
    }

    public function hasManyThrough( $related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null ): HasManyThrough
    {
        /** @var HasManyThrough $parent */
        $parent = parent::hasManyThrough( $related, $through, $firstKey, $secondKey, $localKey, $secondLocalKey );

        return $parent;
    }

    public function morphMany( $related, $name, $type = null, $id = null, $localKey = null ): MorphMany
    {
        /** @var MorphMany $parent */
        $parent = parent::morphMany( $related, $name, $type, $id, $localKey );

        return $parent;
    }

    public function belongsToMany( $related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null,
                                   $parentKey = null, $relatedKey = null, $relation = null ): BelongsToMany
    {
        /** @var BelongsToMany $parent */
        $parent = parent::belongsToMany( $related, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relation );

        return $parent;
    }

    public function morphToMany( $related, $name, $table = null, $foreignPivotKey = null,
                                 $relatedPivotKey = null, $parentKey = null,
                                 $relatedKey = null, $relation = null, $inverse = false ): MorphToMany
    {
        /** @var MorphToMany $parent */
        $parent = parent::morphToMany( $related, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relation, $inverse );

        return $parent;
    }

    public function morphedByMany( $related, $name, $table = null, $foreignPivotKey = null,
                                   $relatedPivotKey = null, $parentKey = null, $relatedKey = null, $relation = null ): MorphMany
    {
        /** @var MorphMany $parent */
        $parent = parent::morphedByMany( $related, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relation );

        return $parent;
    }

    protected function newHasOne( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $foreignKey, $localKey ): HasOne
    {
        return new HasOne( $query, $parent, $foreignKey, $localKey );
    }

    protected function newHasOneThrough( Builder|IlluminateBuilder $query, Model|IlluminateModel $farParent, Model|IlluminateModel $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey ): HasOneThrough
    {
        return new HasOneThrough( $query, $farParent, $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey );
    }

    protected function newMorphOne( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $type, $id, $localKey ): MorphOne
    {
        return new MorphOne( $query, $parent, $type, $id, $localKey );
    }

    protected function newBelongsTo( Builder|IlluminateBuilder $query, Model|IlluminateModel $child, $foreignKey, $ownerKey, $relation ): BelongsTo
    {
        return new BelongsTo( $query, $child, $foreignKey, $ownerKey, $relation );
    }

    protected function morphEagerTo( $name, $type, $id, $ownerKey ): MorphTo
    {
        return $this->newMorphTo(
            $this->newQuery()->setEagerLoads( [] ), $this, $id, $ownerKey, $type, $name
        );
    }

    protected function morphInstanceTo( $target, $name, $type, $id, $ownerKey ): MorphTo
    {
        $instance = $this->newRelatedInstance(
            static::getActualClassNameForMorph( $target )
        );

        return $this->newMorphTo(
            $instance->newQuery(), $this, $id, $ownerKey ?? $instance->getKeyName(), $type, $name
        );
    }

    protected function newMorphTo( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $foreignKey, $ownerKey, $type, $relation ): MorphTo
    {
        return new MorphTo( $query, $parent, $foreignKey, $ownerKey, $type, $relation );
    }

    protected function newHasMany( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $foreignKey, $localKey ): HasMany
    {
        return new HasMany( $query, $parent, $foreignKey, $localKey );
    }

    protected function newHasManyThrough( Builder|IlluminateBuilder $query, Model|IlluminateModel $farParent, Model|IlluminateModel $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey ): HasManyThrough
    {
        return new HasManyThrough( $query, $farParent, $throughParent, $firstKey, $secondKey, $localKey, $secondLocalKey );
    }

    protected function newMorphMany( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $type, $id, $localKey ): MorphMany
    {
        return new MorphMany( $query, $parent, $type, $id, $localKey );
    }

    protected function newBelongsToMany( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $table, $foreignPivotKey, $relatedPivotKey,
                                                 $parentKey, $relatedKey, $relationName = null ): BelongsToMany
    {
        return new BelongsToMany( $query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName );
    }

    protected function newMorphToMany( Builder|IlluminateBuilder $query, Model|IlluminateModel $parent, $name, $table, $foreignPivotKey,
                                               $relatedPivotKey, $parentKey, $relatedKey,
                                               $relationName = null, $inverse = false ): MorphToMany
    {
        return new MorphToMany( $query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse );
    }
}
