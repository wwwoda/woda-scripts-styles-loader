<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Core\HashHelper;
use Woda\WordPress\ScriptsStylesLoader\Utils\File;

final class JQuery extends Script
{

    /**
     * JQuery constructor.
     * @param string $src
     * @param string $ver
     * @param bool $inFooter
     */
    public function __construct(string $src, string $ver = '', bool $inFooter = false)
    {
        parent::__construct($src, [], 'jquery', $ver, $inFooter);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->ver ?: HashHelper::getVendorScriptHashValue(File::getBasename($this->src));
    }
}
