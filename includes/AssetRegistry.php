<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

final class AssetRegistry
{
    /** @var Script[] */
    public $scripts = [];
    /** @var Style[] */
    public $styles = [];
    /** @var bool */
    public $editorStylesThemeSupport = false;

    public function __construct() {
        $this->registerHooks();
    }

    /**
     * @param Script|Style $asset
     */
    public function addAssetToRegistry($asset): void
    {
        if ($asset instanceof Script) {
            $this->scripts[] = $asset;
            return;
        }
        $this->styles[] = $asset;
    }

    public function addEditorStyles(): void
    {
        foreach ($this->styles as $style) {
            if ($style->shouldEnqueueInEditor() === true) {
                $style->addEditorStyle();
                $this->addThemeSupportForEditorStyles();
            }
        }
    }

    private function addThemeSupportForEditorStyles(): void
    {
        if ($this->editorStylesThemeSupport !== true) {
            add_theme_support('editor-styles');
            $this->editorStylesThemeSupport = true;
        }
    }

    public function processAssetsforAdmin(): void
    {
        foreach ($this->scripts as $script) {
            if ($script->shouldEnqueueInAdmin() === true) {
                $script->enqueue();
            }
        }
        foreach ($this->styles as $style) {
            if ($style->shouldEnqueueInAdmin() === true) {
                $style->enqueue();
            }
        }
    }

    public function processAssetsforFrontend(): void
    {
        foreach ($this->scripts as $script) {
            if ($script->shouldEnqueueInFrontend() === true) {
                $script->enqueue();
            }
        }
        foreach ($this->styles as $style) {
            if ($style->shouldEnqueueInFrontend() === true) {
                $style->enqueue();
            }
        }
    }

    public function processAssetsforLogin(): void
    {
        foreach ($this->scripts as $script) {
            if ($script->shouldEnqueueInLogin() === true) {
                $script->enqueue();
            }
        }
        foreach ($this->styles as $style) {
            if ($style->shouldEnqueueInLogin() === true) {
                $style->enqueue();
            }
        }
    }

    public function updateScriptTags(string $tag, string $handle): string
    {
        foreach ($this->scripts as $script) {
            if ($script->handle !== $handle) {
                continue;
            }
            if ($script->loadDeferred === true) {
                return $script->applyDeferPatternToTag($tag);
            }
            if ($script->loadAsync === true) {
                return $script->applyAsyncPatternToTag($tag);
            }
            return $tag;
        }
        return $tag;
    }

    public function updateStyleTags(string $tag, string $handle): string
    {
        foreach ($this->styles as $style) {
            if ($style->handle !== $handle) {
                continue;
            }
            if ($style->loadAsync === true) {
                return $style->applyAsyncPatternToTag($tag);
            }
            return $tag;
        }
        return $tag;
    }

    private function registerHooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'processAssetsforFrontend']);
        add_action('admin_enqueue_scripts', [$this, 'processAssetsforAdmin']);
        add_action('login_enqueue_scripts', [$this, 'processAssetsforLogin']);
        add_action('after_setup_theme', [$this, 'addEditorStyles']);
        apply_filters('script_loader_tag', [$this, 'updateScriptTags'], 10, 2);
        apply_filters('style_loader_tag', [$this, 'updateStyleTags'], 10, 2);
    }
}
