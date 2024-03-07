<?php

namespace App\Abstractions\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder as IlluminateSeeder;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

abstract class Seeder extends IlluminateSeeder
{
    /**
     * @param  array  $parameters
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __invoke(array $parameters = []): mixed
    {
        if (! method_exists($this, 'run')) {
            throw new InvalidArgumentException('Method [run] missing from '.get_class($this));
        }

        if (method_exists($this, 'truncate')) {
            $this->truncate();
        }

        $callback = fn () => isset($this->container)
            ? $this->container->call([$this, 'run'], $parameters)
            : $this->run(...$parameters);

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[WithoutModelEvents::class])) {
            $callback = $this->withoutModelEvents($callback);
        }

        return $callback();
    }

    protected function disableForeignKeys(): void
    {
        Schema::disableForeignKeyConstraints();
    }

    protected function enableForeignKeys(): void
    {
        Schema::enableForeignKeyConstraints();
    }
}