<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Core\Dependency;
use Woda\WordPress\ScriptsStylesLoader\Core\HashHelper;
use Woda\WordPress\ScriptsStylesLoader\Utils\File;

class Script extends Dependency
{
    /**
     * @var bool
     */
    public $inFooter;

    /**
     * Script constructor.
     * @param string $src
     * @param array $deps
     * @param string $handle
     * @param string $ver
     * @param bool $inFooter
     */
    public function __construct(
        string $src,
        array $deps = [],
        string $handle = '',
        string $ver = '',
        bool $inFooter = true
    )
    {
        parent::__construct($src, $deps, $handle, $ver);
        $this->inFooter = $inFooter;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->ver ?: HashHelper::getScriptHashValue(File::getBasename($this->src));
    }

    /**
     * @return string
     */
    public function getInFooter(): string
    {
        return $this->inFooter;
    }
}
