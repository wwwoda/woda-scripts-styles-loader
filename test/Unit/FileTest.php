<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\WordPress\ScriptsStylesLoader\File;

final class FileTest extends AbstractTestCase
{
    public function testGetBaseName(): void
    {
        self::assertSame('main.js', (new File('https://www.woda.at/assets/dist/js/main.js'))->getBaseName());
    }

    public function testGetFileName(): void
    {
        self::assertSame('main', (new File('https://www.woda.at/assets/dist/js/main.js'))->getFileName());
    }
}
