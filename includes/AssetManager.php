<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class AssetManager
{
    /** @var AssetRegistry */
    private $assetRegistry;

    public function __construct() {
        $this->assetRegistry = new AssetRegistry();
    }

    /**
     * @param AbstractAsset[]|AbstractAsset $asset
     *
     * @return AssetManager
     */
    public function addAssets($assets): AssetManager
    {
        if (is_array($assets)) {
            foreach ($assets as $asset) {
                $this->assetRegistry->addAssetToRegistry($asset);
            }
        } else {
            $this->assetRegistry->addAssetToRegistry($assets);
        }
        return $this;
    }
}
