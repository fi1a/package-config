<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\PackageConfig\File;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Файл
 */
class FileTest extends TestCase
{
    /**
     * Путь до файла
     */
    public function testPath(): void
    {
        $file = new File('vendor/fi1a/foo/configs/web.php');
        $this->assertEquals('vendor/fi1a/foo/configs/web.php', $file->getPath());
    }

    /**
     * Путь до файла
     */
    public function testPathException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new File('');
    }
}
