<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class ScriptFactory
{
    /**
     * @param string           $src       Full URL of the script, or path of the script relative to the WordPress root
     *                                    directory.
     * @param string[]         $deps      Optional. An array of registered script handles this script depends on.
     *                                    Default empty array.
     * @param string           $handle    Optional. Name of the script. Should be unique.
     *                                    If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver       Optional. String specifying script version number, if it has one, which is
     *                                    added to the URL as a query string for cache busting purposes. If version is
     *                                    set to false, a version number is automatically added equal to current
     *                                    installed WordPress version. If set to null, no version is added.
     * @param bool             $inFooter  Optional. Whether to enqueue the script before </body> instead of in the
     *                                    <head>. Default 'false'.
     */
    public function create(
        string $src,
        ?array $deps = null,
        ?string $handle = null,
        $ver = false,
        bool $inFooter = false
    ): ScriptInterface
    {
        return new Script($src, $deps, $handle, $ver, $inFooter);
    }
}
