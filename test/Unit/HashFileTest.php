<?php

declare(strict_types=1);

namespace Woda\Test\WordPress\ScriptsStylesLoader\Unit;

use Woda\WordPress\ScriptsStylesLoader\HashFile;

final class HashFileTest extends AbstractTestCase
{
    public function testGetHashValue(): void
    {
        self::assertSame('1d9123db4a4e7d861577', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('main.js'));
        self::assertSame('', (new HashFile(__DIR__ . '/../hash-file.json'))->getHashValue('foo.js'));
    }
}
