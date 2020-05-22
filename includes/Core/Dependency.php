<?php

namespace Woda\WordPress\ScriptsStylesLoader\Core;

use Woda\WordPress\ScriptsStylesLoader\Settings;
use Woda\WordPress\ScriptsStylesLoader\Utils\File;

abstract class Dependency
{
    /**
     * @var string
     */
    public $handle;
    /**
     * @var string
     */
    public $src;
    /**
     * @var array
     */
    public $deps = [];
    /**
     * @var string
     */
    public $ver;

    /**
     * Dependency constructor.
     * @param string $src
     * @param array $deps
     * @param string $handle
     * @param string $ver
     */
    public function __construct(string $src, array $deps = [], string $handle = '', string $ver = '')
    {
        $this->src = $src;
        $this->deps = $deps;
        $this->handle = $handle;
        $this->ver = $ver;
    }

    /**
     * @param string $handle
     * @return string
     */
    public function getHandle($handle = ''): string {
        return $this->handle ?: Settings::getHandlePrefix() . '-' . File::getFilename($this->src);
    }

    /**
     * @param string $handle
     * @return string
     */
    public function getSource($handle = ''): string {
        return $this->src;
    }

    /**
     * @param string $handle
     * @return array
     */
    public function getDependencies($handle = ''): array {
        return $this->deps;
    }
}
