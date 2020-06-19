<?php

namespace Woda\WordPress\ScriptsStylesLoader;

final class HashFile
{
    /**
     * @var array
     */
    private $values;

    /**
     * HashFile constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->values = json_decode(file_get_contents($path), true);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHashValue($name): string
    {
        return $this->values[$name] ?? '';
    }
}
