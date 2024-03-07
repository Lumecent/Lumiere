<?php

namespace App\Abstractions\Http;

use App\Abstractions\Domain\Identicon;
use App\Abstractions\Facades\Storage;
use App\Abstractions\Facades\Str;
use Illuminate\Http\UploadedFile as IlluminateUploadedFile;
use App\Abstractions\Testing\FileFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadedFile extends IlluminateUploadedFile
{
    public static function fake(): FileFactory
    {
        return new FileFactory;
    }

    public static function createFromBase(SymfonyUploadedFile $file, $test = false): UploadedFile|static
    {
        return $file instanceof static ? $file : new static(
            $file->getPathname(),
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            $file->getError(),
            $test
        );
    }

    /**
     * Генерирует случайное изображение для пользователя при регистрации.
     * Генерирует случайное изображение для художника при создании страницы.
     * Генерирует случайное изображение для модератора при создании страницы.
     *
     * Сохраняет изображение на диске в директории хранилища.
     * После обработки и сохранении файла в публичной директории необходимо удалить файл из хранилища,
     * для этого нужно использовать метод удаления файла в классе-хелпере файловой системы (FilesystemHelper)
     * и передать в него исходный путь до изображения ($uploadedFile->getRealPath())
     *
     * @return UploadedFile
     */
    public static function newAvatarInstance(): self
    {
        $avatar = new Identicon( [
            'value' => Str::random(),
            'size' => 160
        ] );

        $avatarName = Str::random( 48 ) . '.jpg';

        Storage::disk( 'temp' )->put( $avatarName, $avatar->getImageData() );

        $path = storage_path() . '/temp/' . $avatarName;

        return new UploadedFile( $path, $avatarName );
    }
}