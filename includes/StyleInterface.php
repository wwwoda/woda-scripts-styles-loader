<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

interface StyleInterface extends AbstractAssetInterface
{
    public function enqueueEditor(): StyleInterface;
}
