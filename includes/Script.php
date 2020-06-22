<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class Script extends Asset
{
    /** @var bool */
    public $inFooter;

    /**
     *
     * @param string           $src       Full URL of the script, or path of the script relative to the WordPress root
     *                                    directory.
     * @param string[]         $deps      Optional. An array of registered script handles this script depends on.
     *                                    Default empty array.
     * @param string           $handle    Optional. Name of the script. Should be unique.
     *                                    If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver       Optional. String specifying script version number, if it has one, which is
     *                                    added to the URL as a query string for cache busting purposes. If version is
     *                                    set to false, a version number is automatically added equal to current
     *                                    installed WordPress version. If set to null, no version is added.
     * @param bool             $inFooter  Optional. Whether to enqueue the script before </body> instead of in the
     *                                    <head>. Default 'false'.
     */
    public function __construct(
        string $src,
        ?array $deps = null,
        ?string $handle = null,
        $ver = false,
        bool $inFooter = false
    ) {
        parent::__construct($src, $deps, $handle, $ver);
        $this->inFooter = $inFooter;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function deregister(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'deregisterScript'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function deregisterAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'deregisterScript'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function enqueue(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScript'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function enqueueAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueScript'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this|Asset
     */
    public function loadAsync(int $priority = 10): Asset
    {
        add_action('script_loader_tag', [$this, 'addAsyncAttribute'], $priority, 2);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this|Asset
     */
    public function loadDeferred(int $priority = 10): Asset
    {
        add_action('script_loader_tag', [$this, 'addDeferAttribute'], $priority, 2);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function register(int $priority = 10): Script
    {
        add_action('wp_enqueue_scripts', [$this, 'registerScript'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function registerAdmin(int $priority = 10): Script
    {
        add_action('admin_enqueue_scripts', [$this, 'registerScript'], $priority);
        return $this;
    }

    /**
     * @param string $tag    The <script> tag for the enqueued script.
     * @param string $handle The script's registered handle.
     */
    public function addAsyncAttribute(string $tag, string $handle): string
    {
        if ($handle === $this->handle) {
            return str_replace(' src', ' async=\'async\' src', $tag);
        }
        return $tag;
    }

    /**
     * @param string $tag    The <script> tag for the enqueued script.
     * @param string $handle The script's registered handle.
     */
    public function addDeferAttribute(string $tag, string $handle): string
    {
        if ($handle === $this->handle) {
            return str_replace(' src', ' defer=\'defer\' src', $tag);
        }
        return $tag;
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
