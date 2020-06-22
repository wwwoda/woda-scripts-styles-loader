<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\Test\WordPress\ScriptsStylesLoader\Unit\Helper\WpMockHelper;
use Woda\WordPress\ScriptsStylesLoader\Style;

final class StyleTest extends AbstractTestCase
{
    private const SRC = 'style.css';
    private const DEPS = ['bootstrap'];
    private const HANDLE = 'handle';
    private const VER = '1.0.0';
    private const MEDIA = 'screen';

    /** @var Style */
    private $style;

    public function setUp(): void
    {
        parent::setUp();
        $this->style = new Style(
            self::SRC,
            self::DEPS,
            self::HANDLE,
            self::VER,
            self::MEDIA
        );
    }

    public function testEnqueue(): void
    {
        \WP_Mock::expectActionAdded('wp_enqueue_scripts', [$this->style, 'enqueueStyle'], 1);
        $this->style->enqueue(1);
    }

    public function testEnqueueAdmin(): void
    {
        \WP_Mock::expectActionAdded('admin_enqueue_scripts', [$this->style, 'enqueueStyle'], 1);
        $this->style->enqueueAdmin(1);
    }

    public function testEnqueueEditor(): void
    {
        \WP_Mock::expectActionAdded('after_setup_theme', [$this->style, 'addEditorStyle'], 1);
        $this->style->enqueueEditor(1);
    }

    public function testLoadAsync(): void
    {
        \WP_Mock::expectActionAdded('style_loader_tag', [$this->style, 'applyAsyncPatternToTag'], 1, 2);
        $this->style->loadAsync(1);
    }

    public function testAddEditorStyle(): void
    {
        WpMockHelper::expectCall('add_theme_support', ['editor-styles']);
        WpMockHelper::expectCall('add_editor_style', [self::SRC]);
        $this->style->addEditorStyle();
    }

    public function testEnqueueStyle(): void
    {
        WpMockHelper::expectCall('wp_enqueue_style', [
            self::HANDLE,
            self::SRC,
            self::DEPS,
            self::VER,
            self::MEDIA,
        ]);
        $this->style->enqueueStyle();
    }

    public function testApplyAsyncPatternToTag(): void
    {
        self::assertSame(
            '<link rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id=\'woda-main-css\'' .
            '  href=\'style.css\' type=\'text/css\' media=\'all\' />' .
            '<noscript><link rel="stylesheet" href=\'style.css\'></noscript>',
            $this->style->applyAsyncPatternToTag(
                '<link rel=\'stylesheet\' id=\'woda-main-css\'  href=\'style.css\' type=\'text/css\' media=\'all\' />',
                $this->style->handle
            )
        );
        self::assertSame(
            '<style></style>',
            $this->style->applyAsyncPatternToTag('<style></style>', 'foobar')
        );
    }
}
