<?php

namespace App\Utilities\Helpers;

class StringHelper
{
    /**
     * Удаляет все повторяющиеся пробелы из строки и заменяет переносы строк на пробел
     *
     * @param string $string
     * @return string
     */
    public static function removeSpaces( string $string ): string
    {
        return self::trim( preg_replace( '/[ \s]+/u', ' ', $string ) );
    }

    /**
     * Удаляет из строки все теги, кроме разрешённых
     *
     * @param string $string
     * @param array $allowedTags
     * @return string
     */
    public static function clearHtmlTags( string $string, array $allowedTags = [] ): string
    {
        return htmlspecialchars_decode( strip_tags( $string, $allowedTags ) );
    }

    /**
     * Удаляет с начала и конца строки пробелы и переданные символы
     *
     * @param string $string
     * @param string|string[] $trim_chars
     * @return string
     */
    public static function trim( string $string, array|string $trim_chars = '' ): string
    {
        return (string)preg_replace( '/^[\s' . $trim_chars . ']*(?U)(.*)[\s' . $trim_chars . ']*$/u', '\\1', $string );
    }
}