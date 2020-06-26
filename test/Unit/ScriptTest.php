<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\Test\WordPress\ScriptsStylesLoader\Unit\Helper\WpMockHelper;
use Woda\WordPress\ScriptsStylesLoader\Script;

final class ScriptTest extends AbstractTestCase
{
    private const SRC = 'script.js';
    private const DEPS = ['jquery'];
    private const HANDLE = 'handle';
    private const VER = '1.0.0';
    private const IN_FOOTER = true;

    /** @var Script */
    private $script;

    public function setUp(): void
    {
        parent::setUp();
        $this->script = new Script(
            self::SRC,
            self::DEPS,
            self::HANDLE,
            self::VER,
            self::IN_FOOTER
        );
    }

    public function testDeregister(): void
    {
        \WP_Mock::expectActionAdded('wp_enqueue_scripts', [$this->script, 'deregisterScript'], 1);

        $this->script->deregister(1);
    }

    public function testDeregisterAdmin(): void
    {
        \WP_Mock::expectActionAdded('admin_enqueue_scripts', [$this->script, 'deregisterScript'], 1);

        $this->script->deregisterAdmin(1);
    }

    public function testEnqueue(): void
    {
        \WP_Mock::expectActionAdded('wp_enqueue_scripts', [$this->script, 'enqueueScript'], 1);

        $this->script->enqueue(1);
    }

    public function testEnqueueAdmin(): void
    {
        \WP_Mock::expectActionAdded('admin_enqueue_scripts', [$this->script, 'enqueueScript'], 1);

        $this->script->enqueueAdmin(1);
    }

    public function testLoadAsync(): void
    {
        \WP_Mock::expectActionAdded('script_loader_tag', [$this->script, 'addAsyncAttribute'], 1, 2);

        $this->script->loadAsync(1);
    }

    public function testLoadDeferred(): void
    {
        \WP_Mock::expectActionAdded('script_loader_tag', [$this->script, 'addDeferAttribute'], 1, 2);

        $this->script->loadDeferred(1);
    }

    public function testRegister(): void
    {
        \WP_Mock::expectActionAdded('wp_enqueue_scripts', [$this->script, 'registerScript'], 1);

        $this->script->register(1);
    }

    public function testRegisterAdmin(): void
    {
        \WP_Mock::expectActionAdded('admin_enqueue_scripts', [$this->script, 'registerScript'], 1);

        $this->script->registerAdmin(1);
    }

    public function testAddAsyncAttribute(): void
    {
        self::assertSame(
            '<script type=\'text/javascript\' async=\'async\' src=\'script.js\'></script>',
            $this->script->addAsyncAttribute(
                '<script type=\'text/javascript\' src=\'script.js\'></script>',
                $this->script->handle
            )
        );
    }

    public function testAddDeferAttribute(): void
    {
        self::assertSame(
            '<script type=\'text/javascript\' defer=\'defer\' src=\'script.js\'></script>',
            $this->script->addDeferAttribute(
                '<script type=\'text/javascript\' src=\'script.js\'></script>',
                $this->script->handle
            )
        );
    }

    public function testDeregisterScript(): void
    {
        WpMockHelper::expectCall('wp_deregister_script', [$this->script->handle]);

        $this->script->deregisterScript();
    }

    public function testEnqueueScript(): void
    {
        WpMockHelper::expectCall('wp_enqueue_script', [
            self::HANDLE,
            self::SRC,
            self::DEPS,
            self::VER,
            self::IN_FOOTER,
        ]);

        $this->script->enqueueScript();
    }

    public function testRegisterScript(): void
    {
        WpMockHelper::expectCall('wp_register_script', [
            self::HANDLE,
            self::SRC,
            self::DEPS,
            self::VER,
            self::IN_FOOTER,
        ]);

        $this->script->registerScript();
    }
}
