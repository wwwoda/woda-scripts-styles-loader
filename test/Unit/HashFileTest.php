<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\WordPress\ScriptsStylesLoader\HashFile;

final class HashFileTest extends AbstractTestCase
{
    public function testGetHashValue(): void
    {
        self::assertSame('script-hash', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('script.js'));
        self::assertSame('style-hash', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('style.css'));
        self::assertSame('', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('foo.js'));
    }
}
