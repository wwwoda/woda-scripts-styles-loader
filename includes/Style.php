<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class Style extends Asset
{
    /** @var string */
    public $media;

    /**
     *
     * @param string           $src    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress
     *                                 root directory.
     * @param string[]         $deps   Optional. An array of registered stylesheet handles this stylesheet depends on.
     *                                 Default empty array.
     * @param string           $handle Optional. Name of the stylesheet. Should be unique.
     *                                 If empty, handle will be generated from prefix and file name.
     * @param string|bool|null $ver    Optional. String specifying stylesheet version number, if it has one, which is
     *                                 added to the URL as a query string for cache busting purposes. If version is set
     *                                 to false, a version number is automatically added equal to current installed
     *                                 WordPress version. If set to null, no version is added.
     * @param string           $media  Optional. The media for which this stylesheet has been defined.
     *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media
     *                                 queries like '(orientation: portrait)' and '(max-width: 640px)'.
     */
    public function __construct(
        string $src,
        ?array $deps = null,
        ?string $handle = null,
        $ver = false,
        string $media = 'all'
    ) {
        parent::__construct($src, $deps, $handle, $ver);
        $this->media = $media;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function enqueue(int $priority = 10): Style
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyle'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function enqueueAdmin(int $priority = 10): Style
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyle'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function enqueueEditor(int $priority = 10): Style
    {
        add_action('after_setup_theme', [$this, 'addEditorStyle'], $priority);
        return $this;
    }

    /**
     * @param int $priority Optional. Execution priority of action hook. Default 10.
     *
     * @return $this
     */
    public function loadAsync(int $priority = 10): Style
    {
        add_action('style_loader_tag', [$this, 'applyAsyncPatternToTag'], $priority, 2);
        return $this;
    }

    public function addEditorStyle(): void
    {
        add_theme_support('editor-styles');
        add_editor_style($this->src);
    }

    public function enqueueStyle(): void
    {
        wp_enqueue_style(
            $this->handle,
            $this->src,
            $this->deps,
            $this->getVersion(),
            $this->media
        );
    }

    /**
     * @param string $tag    The <style> tag for the enqueued style.
     * @param string $handle The style's registered handle.
     */
    public function applyAsyncPatternToTag(string $tag, string $handle): string
    {
        if ($handle === $this->handle) {
            $tag = str_replace(
                ' rel=\'stylesheet\'',
                ' rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
                $tag
            );
            $tag .= sprintf('<noscript><link rel="stylesheet" %s></noscript>', $this->extractHrefFromTag($tag));
        }
        return $tag;
    }

    private function extractHrefFromTag(string $tag): string
    {
        preg_match('/(href=\'.*?\')/', $tag, $matches, PREG_OFFSET_CAPTURE);
        if (isset($matches[0])) {
            return $matches[0][0];
        }
        return '';
    }
}
