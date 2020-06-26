<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\WordPress\ScriptsStylesLoader\HashFile;

final class HashFileTest extends AbstractTestCase
{
    public function testGetHashValueReturnsCorrectHash(): void
    {
        self::assertSame('script-hash', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('script.js'));
    }

    public function testGetHashValueReturnsEmptyStringWhenNoEntryFound(): void
    {
        self::assertSame('', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('foo.js'));
    }
}
