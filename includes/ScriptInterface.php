<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

interface ScriptInterface extends AbstractAssetInterface
{
    public function loadDeferred(): ScriptInterface;
}
