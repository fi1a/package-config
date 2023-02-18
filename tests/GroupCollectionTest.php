<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\PackageConfig\FileCollection;
use Fi1a\PackageConfig\GroupCollection;
use PHPUnit\Framework\TestCase;

/**
 * Коллекция групп
 */
class GroupCollectionTest extends TestCase
{
    /**
     * Коллекция файлов
     */
    public function testCollection(): void
    {
        $collection = new GroupCollection();
        $collection[] = new FileCollection([
            [
                'file' => 'vendor/fi1a/foo/configs/web.php',
            ],
            [
                'file' => 'vendor/fi1a/foo/configs/web2.php',
            ],
        ]);
        $collection[] = [
            [
                'file' => 'vendor/fi1a/foo/configs/web.php',
            ],
            [
                'file' => 'vendor/fi1a/foo/configs/web2.php',
            ],
        ];
        $this->assertCount(2, $collection);
    }
}
