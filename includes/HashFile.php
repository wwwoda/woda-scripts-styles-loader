<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class HashFile
{
    /** @var array<string, string> */
    private $values;

    public function __construct(string $path)
    {
        $content = file_get_contents($path);
        $this->values = $content ? json_decode($content, true) : [];
    }

    public function getHashValue(string $name): string
    {
        return $this->values[$name] ?? '';
    }
}
