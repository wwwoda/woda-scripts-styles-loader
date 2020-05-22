<?php

namespace Woda\WordPress\ScriptsStylesLoader\Utils;

final class File {
    /**
     * @param string $path
     * @return string
     */
    public static function getBasename(string $path): string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getFilename(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * @param string $filename
     * @param string $errorTemplate
     * @return bool
     */
    public static function exists(string $filename = '', string $errorTemplate = ''): bool
    {
        if (!file_exists($filename)) {
            if (!empty($errorTemplate)) {
                Error::notice(sprintf($errorTemplate, $filename));
            } else {
                Error::notice('"' . $filename . '" doesn\'t exist.');
            }
            return false;
        }
        return true;
    }
}
