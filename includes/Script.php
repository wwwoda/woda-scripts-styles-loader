<?php

namespace Woda\WordPress\ScriptsStylesLoader;

final class Script extends Asset
{
    /**
     * @var bool
     */
    public $inFooter;

    /**
     * Script constructor.
     * @param string           $src       Full URL of the script, or path of the script relative to the WordPress root directory.
     * @param string[]         $deps      Optional. An array of registered script handles this script depends on. Default empty array.
     * @param string           $handle    Optional. Name of the script. Should be unique.
     *                                    If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver       Optional. String specifying script version number, if it has one, which is added to the URL
     *                                    as a query string for cache busting purposes. If version is set to false, a version
     *                                    number is automatically added equal to current installed WordPress version.
     *                                    If set to null, no version is added.
     * @param string           $inFooter  Optional. Whether to enqueue the script before </body> instead of in the <head>.
     *                                    Default 'false'.
     */
    public function __construct(string $src, ?array $deps = [], ?string $handle = '', $ver = false, bool $inFooter = false)
    {
        parent::__construct($src, $deps, $handle, $ver);
        $this->inFooter = $inFooter;
    }

    public function deregister(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'deregisterScript'], $priority);
        return $this;
    }

    public function deregisterAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'deregisterScript'], $priority);
        return $this;
    }

    public function enqueue(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScript'], $priority);
        return $this;
    }

    public function enqueueAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueScript'], $priority);
        return $this;
    }

    public function register(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'registerScript'], $priority);
        return $this;
    }

    public function registerAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'registerScript'], $priority);
        return $this;
    }

    public function deregisterScript(): void
    {
        wp_deregister_script($this->handle);
    }

    public function enqueueScript(): void
    {
        wp_enqueue_script(
            $this->handle,
            $this->src,
            $this->deps,
            $this->getVersion(),
            $this->inFooter
        );
    }

    public function registerScript(): void
    {
        wp_register_script(
            $this->handle,
            $this->src,
            $this->deps,
            $this->getVersion(),
            $this->inFooter
        );
    }
}
