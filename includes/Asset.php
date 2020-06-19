<?php

namespace Woda\WordPress\ScriptsStylesLoader;

abstract class Asset
{
    /**
     * @var array
     */
    public $deps = [];
    /**
     * @var File
     */
    public $file;
    /**
     * @var string
     */
    public $handle;
    /**
     * @var string
     */
    public $hash;
    /**
     * @var int
     */
    public $src;
    /**
     * @var string
     */
    public $ver;

    /**
     * Asset constructor.
     * @param string           $src    Full URL of the script, or path of the asset relative to the WordPress root directory.
     * @param string[]         $deps   Optional. An array of registered asset handles this asset depends on. Default empty array.
     * @param string           $handle Optional. Name of the asset. Should be unique.
     *                                 If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver    Optional. String specifying asset version number, if it has one, which is added to the URL
     *                                 as a query string for cache busting purposes. If version is set to false, a version
     *                                 number is automatically added equal to current installed WordPress version.
     *                                 If set to null, no version is added.
     */
    public function __construct(string $src, ?array $deps = [], ?string $handle = '', $ver = false)
    {
        $this->file = new File($src);
        $this->src = $src;
        $this->deps = $deps ?: [];
        $this->handle = $this->generateHandle($handle);
        $this->ver = $ver;
    }

    /**
     * @param HashFile $hashFile
     * @param string   $key      Optional. Lookup key of hash value.
     * @return $this
     */
    public function addHashFile(HashFile $hashFile, string $key = ''): Asset
    {
        $this->hash = $hashFile->getHashValue($key ?: $this->file->getBaseName());;
        return $this;
    }

    public function calculateHashValue(): Asset
    {
        $this->hash = md5($this->src);
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        if (!empty($this->hash)) {
            return $this->hash;
        }
        if (is_string($this->ver)) {
            return $this->ver;
        }
        if ($this->ver === false) {
            return get_bloginfo('version');
        }
        return '';
    }

    /**
     * @param string $handle
     * @return string
     */
    private function generateHandle(?string $handle): string
    {
        if (!empty($handle)) {
            return $handle;
        }
        $defaultPrefix = Config::loadConfigString('handle_prefix');
        $prefix = apply_filters('woda-scripts-styles-loader-prefix', $defaultPrefix);
        return $prefix . $this->file->getFileName();
    }
}
