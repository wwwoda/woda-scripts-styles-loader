<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class StyleFactory
{
    /**
     * @param string           $src    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress
     *                                 root directory.
     * @param string[]         $deps   Optional. An array of registered stylesheet handles this stylesheet depends on.
     *                                 Default empty array.
     * @param string           $handle Optional. Name of the stylesheet. Should be unique.
     *                                 If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver    Optional. String specifying stylesheet version number, if it has one, which is
     *                                 added to the URL as a query string for cache busting purposes. If version is set
     *                                 to false, a version number is automatically added equal to current installed
     *                                 WordPress version. If set to null, no version is added.
     * @param string           $media  Optional. The media for which this stylesheet has been defined.
     *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media
     *                                 queries like '(orientation: portrait)' and '(max-width: 640px)'.
     */
    public function create(
        string $src,
        ?array $deps = null,
        ?string $handle = null,
        $ver = false,
        string $media = 'all'
    ): StyleInterface
    {
        return new Style($src, $deps, $handle, $ver, $media);
    }
}
