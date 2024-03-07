<?php

namespace App\Utilities\Helpers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;

class FilesystemHelper
{
    private static Filesystem|null $filesystem = null;

    /**
     * Создаёт директорию по указанному пути, если её нет
     *
     * @param string $path
     * @return bool
     */
    public static function createDir( string $path ): bool
    {
        if ( self::existsDir( $path ) ) {
            return true;
        }
        return self::getFilesystem()->makeDirectory( $path );
    }

    /**
     * Проверяет наличие директории по указанному пути и является ли она директорией, а не файлом
     *
     * @param string $path
     * @return bool
     */
    public static function existsDir( string $path ): bool
    {
        return self::getFilesystem()->exists( $path ) && self::getFilesystem()->isDirectory( $path );
    }

    /**
     * Возвращает список директорий по указанному пути
     *
     * @param string $path
     * @return array
     */
    public static function getDirectories( string $path ): array
    {
        return self::getFilesystem()->directories( $path );
    }

    /**
     * Возвращает список файлов по указанному пути
     *
     * @param string $path
     * @return array
     */
    public static function getFiles( string $path ): array
    {
        return self::getFilesystem()->files( $path );
    }

    /**
     * Возвращает содержимое файла
     *
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     */
    public static function getContentFile( string $path ): string
    {
        if ( self::existsFile( $path ) ) {
            return self::getFilesystem()->get( $path );
        }
        throw new InvalidArgumentException( "File '$path' not found!" );
    }

    /**
     * Создаёт файл по указанному пути
     *
     * @param string $path
     * @param string $content
     * @return bool
     */
    public static function createFile( string $path, string $content ): bool
    {
        return self::getFilesystem()->put( $path, $content );
    }

    /**
     * Проверяет наличие файла по указанному пути и является ли он файлом, а не директорией
     *
     * @param string $path
     * @return bool
     */
    public static function existsFile( string $path ): bool
    {
        return self::getFilesystem()->exists( $path ) && self::getFilesystem()->isFile( $path );
    }

    /**
     * Удаляет файл по указанному пути
     *
     * @param string $path
     * @return bool
     */
    public static function deleteFile( string $path ): bool
    {
        if ( self::existsFile( $path ) ) {
            return self::getFilesystem()->delete( $path );
        }
        throw new InvalidArgumentException( "File '$path' not found!" );
    }

    private static function getFilesystem(): Filesystem
    {
        if ( is_null( self::$filesystem ) ) {
            self::$filesystem = new Filesystem();
        }
        return self::$filesystem;
    }
}
