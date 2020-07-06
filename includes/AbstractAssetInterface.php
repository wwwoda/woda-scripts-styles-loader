<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

interface AbstractAssetInterface
{
    public function addHashFile(HashFile $hashFile, ?string $key = null): AbstractAssetInterface;
    public function calculateHashValue(): AbstractAssetInterface;
    public function enqueueAdmin(): AbstractAssetInterface;
    public function enqueueFrontend(): AbstractAssetInterface;
    public function enqueueLogin(): AbstractAssetInterface;
    public function loadAsync(): AbstractAssetInterface;
}
