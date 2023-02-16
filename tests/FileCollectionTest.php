<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\PackageConfig\File;
use Fi1a\PackageConfig\FileCollection;
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
        $collection[] = new File('vendor/fi1a/foo/configs/web.php');
        $collection[] = 'vendor/fi1a/foo/configs/params.php';
        $this->assertCount(2, $collection);
    }
}
