<?php

namespace Woda\WordPress\ScriptsStylesLoader;

final class File {
    /**
     * @var string
     */
    private $path;

    /**
     * File constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getBaseName(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }
}
