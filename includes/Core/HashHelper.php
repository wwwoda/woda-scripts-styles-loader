<?php

namespace Woda\WordPress\ScriptsStylesLoader\Core;

use Woda\WordPress\ScriptsStylesLoader\Settings;
use Woda\WordPress\ScriptsStylesLoader\Utils\Error;

final class HashHelper
{
    /**
     * @var array
     */
    private static $cache = [];
    /**
     * @param string $basename
     * @return string
     */
    public static function getScriptHashValue(string $basename): string
    {
        return self::getHashValue(Settings::getScriptsHashesFilePath(), $basename);
    }

    /**
     * @param string $basename
     * @return string
     */
    public static function getStyleHashValue(string $basename): string
    {
        return self::getHashValue(Settings::getStylesHashesFilePath(), $basename);
    }

    /**
     * @param string $basename
     * @return string
     */
    public static function getVendorScriptHashValue(string $basename): string
    {
        return self::getHashValue(Settings::getVendorScriptsHashesFilePath(), $basename);
    }

    /**
     * @param string $hashesFilePath
     * @param string $basename
     * @return string
     */
    private static function getHashValue(string $hashesFilePath, string $basename): string
    {
        if (empty($hashesFilePath)) {
            return '';
        }
        if (!isset(self::$cache[$hashesFilePath])) {
            self::$cache[$hashesFilePath] = json_decode(file_get_contents($hashesFilePath), true);
        }
        if (empty(self::$cache[$hashesFilePath][$basename])) {
            Error::notice('Hash value not found for "' . $basename . '"');
        }
        return self::$cache[$hashesFilePath][$basename] ?? '';
    }
}
