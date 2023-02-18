<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\PackageConfig\File;
use Fi1a\PackageConfig\FileCollection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Коллекция файлов
 */
class FileCollectionTest extends TestCase
{
    /**
     * Коллекция файлов
     */
    public function testCollection(): void
    {
        $collection = new FileCollection();
        $collection[] = new File('vendor/fi1a/foo/configs/web.php', 500);
        $collection[] = [
            'sort' => 500,
            'file' => 'vendor/fi1a/foo/configs/params.php',
        ];
        $this->assertCount(2, $collection);
    }

    /**
     * Коллекция файлов
     */
    public function testCollectionException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $collection = new FileCollection();
        $collection[] = 'vendor/fi1a/foo/configs/params.php';
    }
}
