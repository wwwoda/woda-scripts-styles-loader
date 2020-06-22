<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\Test\WordPress\ScriptsStylesLoader\Unit\Helper\WpMockHelper;
use Woda\WordPress\ScriptsStylesLoader\Asset;
use Woda\WordPress\ScriptsStylesLoader\Config;
use Woda\WordPress\ScriptsStylesLoader\HashFile;

final class AssetTest extends AbstractTestCase
{
    private const HANDLE = 'handle';
    private const HANDLE_PREFIX = 'woda-';
    private const CUSTOM_HANDLE_PREFIX = 'adow-';

    /** @var Asset */
    private $asset;
    /** @var HashFile */
    private $hashFile;

    public function setUp(): void
    {
        parent::setUp();
        Config::$config = ['handle_prefix' => self::HANDLE_PREFIX];
        $this->asset = $this->getMockForAbstractClass(Asset::class, ['asset.js']);
        $this->hashFile = new HashFile(__DIR__ . '/../hash-file.json');
    }

    public function testAddHashFile(): void
    {
        $this->asset->addHashFile($this->hashFile);
        self::assertSame('asset-hash', $this->asset->hash);
    }

    public function testGetVersion(): void
    {
        WpMockHelper::expectCall('get_bloginfo', ['version'], '5.4.0');
        self::assertSame('5.4.0', $this->asset->getVersion());
        $asset = $this->getMockForAbstractClass(Asset::class, ['asset.js', [], self::HANDLE, null]);
        self::assertSame('', $asset->getVersion());
        $asset = $this->getMockForAbstractClass(Asset::class, ['asset.js', [], self::HANDLE, 'ver']);
        self::assertSame('ver', $asset->getVersion());
        $asset->addHashFile($this->hashFile);
        self::assertSame('asset-hash', $asset->getVersion());
    }

    public function testAssetHasExpectedHandle(): void
    {
        self::assertSame(self::HANDLE_PREFIX . 'asset', $this->asset->handle);

        \WP_Mock::onFilter('woda-scripts-styles-loader-prefix')
            ->with(self::HANDLE_PREFIX)
            ->reply(self::CUSTOM_HANDLE_PREFIX);
        $asset = $this->getMockForAbstractClass(Asset::class, ['asset.js', [], 'custom-handle']);
        self::assertSame('custom-handle', $asset->handle);
        $asset = $this->getMockForAbstractClass(Asset::class, ['asset.js']);
        self::assertSame(self::CUSTOM_HANDLE_PREFIX . 'asset', $asset->handle);
    }
}
