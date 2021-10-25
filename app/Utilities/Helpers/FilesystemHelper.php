<?php

namespace App\Utilities\Helpers;

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;

class FilesystemHelper
{
    private static Filesystem|null $filesystem = null;

    private static function getFilesystem(): Filesystem
    {
        if ( is_null( self::$filesystem ) ) {
            self::$filesystem = new Filesystem();
        }
        return self::$filesystem;
    }

    public static function existsFile( string $path ): bool
    {
        return self::getFilesystem()->exists( $path ) && self::getFilesystem()->isFile( $path );
    }

    public static function existsDir( string $path ): bool
    {
        return self::getFilesystem()->exists( $path ) && self::getFilesystem()->isDirectory( $path );
    }

    public static function getContentFile( string $path ): string
    {
        if ( self::existsFile( $path ) ) {
            return self::getFilesystem()->get( $path );
        }
        throw new InvalidArgumentException( "File '$path' not found!" );
    }

    public static function createFile( string $path, string $content ): bool
    {
        return self::getFilesystem()->put( $path, $content );
    }

    public static function createDir( string $path ): bool
    {
        if ( self::existsDir( $path ) ) {
            return true;
        }
        return self::getFilesystem()->makeDirectory( $path );
    }
}
