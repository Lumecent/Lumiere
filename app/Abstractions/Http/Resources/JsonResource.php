<?php

namespace App\Abstractions\Http\Resources;

use App\Abstractions\Collections\Collection;
use App\Abstractions\Database\Models\Model;
use App\Abstractions\Interfaces\ArrayableInterface;
use App\Containers\Image\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource as IlluminateResource;
use Illuminate\Support\Str;

/**
 * @property Model|null $resource
 */
class JsonResource extends IlluminateResource
{
    /**
     * Флаг, который определяет необходимость загрузки связанных данных
     * @var bool
     */
    public static bool $includeRelations = true;

    /**
     * Список связанных данных, которые необходимо загрузить и сериализовать
     * @var array
     */
    protected static array $relations = [];
    /**
     * Список аксессоров, которые необходимо сериализовать
     * @var array
     */
    protected static array $appends = [];
    /**
     * Список полей модели, которые необходимо сериализовать
     * @var array
     */
    protected static array $visible = [];
    /**
     * Список полей модели, которые необходимо скрыть
     * @var array
     */
    protected static array $hidden = [];

    public function toArray( $request ): array
    {
        if ( !$this->resource ) {
            return [];
        }

        $this->resource
            ->makeVisible( static::$visible )
            ->makeHidden( static::$hidden );

        $result = $this->resource->attributesToArray();

        foreach ( static::$appends as $attribute ) {
            $result[ $attribute ] = $this->serializeAttribute( $attribute );
        }

        if ( static::$includeRelations ) {
            $this->prepareEagerLoadingRelations();

            $this->resource->loadMissing( static::$relations );

            foreach ( static::$relations as $relation ) {
                if ( Str::contains( $relation, '.' ) ) {
                    continue;
                }

                $result[ $relation ] = $this->serializeRelation( $relation, $request );
            }
        }

        return $result;
    }

    public function serializeImageRelation(): ?ImageResource
    {
        return $this->resource?->image ? ImageResource::make( $this->resource?->image ) : null;
    }

    public static function setRelations( array $relations ): void
    {
        static::$relations = $relations;
    }

    public static function setRelation( array $relation ): void
    {
        static::$relations = array_merge( static::$relations, $relation );
    }

    public static function setAppends( array $appends ): void
    {
        static::$appends = $appends;
    }

    public static function setVisible( array $visible ): void
    {
        static::$visible = $visible;
    }

    public static function setHidden( array $hidden ): void
    {
        static::$hidden = $hidden;
    }

    protected function prepareEagerLoadingRelations(): void
    {
        $relations = Collection::make( static::$relations );

        $parentsRelations = $relations
            ->filter( fn( $relation ): bool => str_contains( $relation, '.' ) )
            ->map( fn( $relation ): string => explode( '.', $relation )[ 0 ] );

        static::$relations = $relations->merge( $parentsRelations )->unique()->all();
    }

    protected function serializeAttribute( $attribute ): mixed
    {
        $method = Str::camel( "serialize_{$attribute}_attribute" );

        if ( method_exists( $this, $method ) ) {
            return $this->$method();
        }

        return $this->resource->getAttribute( $attribute );
    }

    protected function serializeRelation( $relation, $request ): mixed
    {
        $method = Str::camel( "serialize_{$relation}_relation" );

        if ( method_exists( $this, $method ) ) {
            return $this->$method();
        }

        $value = $this->resource->getAttribute( $relation );

        if ( !$value ) {
            return null;
        }

        if ( $value instanceof Collection ) {
            if ( $value->isEmpty() ) {
                return [];
            }

            $firstValue = $value->first();

            if ( is_object( $firstValue ) ) {
                /** @var JsonResource $resourceClass */
                $resourceClass = $this->guessResourceClass( $firstValue );

                if ( $resourceClass ) {
                    return $resourceClass::collection( $value );
                }
            }
        }
        elseif ( is_object( $value ) ) {
            /** @var JsonResource $resourceClass */
            $resourceClass = $this->guessResourceClass( $value );

            if ( $resourceClass ) {
                return $resourceClass::make( $value )->toArray( $request );
            }
        }

        if ( $value instanceof ArrayableInterface ) {
            return $value->toArray();
        }

        return $value;
    }

    protected function guessResourceClass( object $object ): ?string
    {
        $namespace = Str::before( static::class, class_basename( static::class ) );

        $modelName = class_basename( $object );

        $resourceClass = $namespace . $modelName;

        return class_exists( $resourceClass ) ? $resourceClass : null;
    }
}