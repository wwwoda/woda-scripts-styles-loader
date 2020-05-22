<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Core\HashHelper;
use Woda\WordPress\ScriptsStylesLoader\Utils\File;

final class VendorScript extends Script
{
    /**
     * VendorScript constructor.
     * @param string $src
     * @param array $deps
     * @param string $handle
     * @param string $ver
     * @param bool $inFooter
     */
    public function __construct(string $src, array $deps = [], string $handle = '', string $ver = '', bool $inFooter = false)
    {
        parent::__construct($src, $deps, $handle, $ver, $inFooter);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->ver ?: HashHelper::getVendorScriptHashValue(File::getBasename($this->src));
    }

    /**
     * @param string $handle
     * @return string
     */
    public function getHandle($handle = ''): string {
        return $this->handle ?: File::getFilename($this->src);
    }
}
