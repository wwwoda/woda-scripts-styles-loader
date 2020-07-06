<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class File
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getBaseName(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    public function getFileName(): string
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }
}
