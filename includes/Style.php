<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Core\Dependency;
use Woda\WordPress\ScriptsStylesLoader\Core\HashHelper;
use Woda\WordPress\ScriptsStylesLoader\Utils\File;

final class Style extends Dependency
{
    /**
     * @var string
     */
    public $media;

    /**
     * Style constructor.
     * @param string $src
     * @param array $deps
     * @param string $handle
     * @param string $ver
     * @param string $media
     */
    public function __construct(
        string $src,
        array $deps = [],
        string $handle = '',
        string $ver = '',
        string $media = 'all'
    )
    {
        parent::__construct($src, $deps, $handle, $ver);
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->ver ?: HashHelper::getStyleHashValue(File::getBasename($this->src));
    }

    /**
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }
}
