<?php

namespace App\Abstractions\Resources;

use App\Abstractions\Interfaces\ArrayableInterface;
use App\Abstractions\Models\Model;
use Illuminate\Http\Resources\Json\JsonResource as IlluminateResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property Model|null $resource
 */
class JsonResource extends IlluminateResource
{
    public static bool $includeRelations = true;
    protected static array $dontAppendAnywhere = [];

    protected array $relations = [];
    protected array $appends = [];
    protected array $visible = [];
    protected array $hidden = [];

    public function toArray( $request ): array
    {
        $this->resource
            ->makeVisible( $this->visible )
            ->makeHidden( $this->hidden );

        $result = $this->resource->attributesToArray();

        foreach ( $this->appends as $attribute ) {
            if ( !in_array( $attribute, static::$dontAppendAnywhere, true ) ) {
                $result[ $attribute ] = $this->serializeAttribute( $attribute );
            }
        }

        if ( static::$includeRelations ) {
            $this->prepareEagerLoadingRelations();

            $this->resource->loadMissing( $this->relations );

            foreach ( $this->relations as $relation ) {
                if ( Str::contains( $relation, '.' ) ) {
                    continue;
                }

                $result[ $relation ] = $this->serializeRelation( $relation, $request );
            }
        }

        return $result;
    }

    protected function prepareEagerLoadingRelations(): void
    {
        $relations = Collection::make( $this->relations );

        $parentsRelations = $relations
            ->filter( fn( $relation ): bool => str_contains( $relation, '.' ) )
            ->map( fn( $relation ): string => explode( '.', $relation )[ 0 ] );

        $this->relations = $relations->merge( $parentsRelations )->unique()->all();
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
                $resourceClass = $this->guessResourceClass( $firstValue );

                if ( $resourceClass ) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    return $resourceClass::collection( $value );
                }
            }
        }
        elseif ( is_object( $value ) ) {
            $resourceClass = $this->guessResourceClass( $value );

            if ( $resourceClass ) {
                /** @noinspection PhpUndefinedMethodInspection */
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