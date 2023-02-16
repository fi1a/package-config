<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use ErrorException;
use Fi1a\PackageConfig\File;
use Fi1a\PackageConfig\Map;
use PHPUnit\Framework\TestCase;

/**
 * Конфигурационные файлы
 */
class MapTest extends TestCase
{
    /**
     * Добавить файл конфигурации
     */
    public function testAdd(): void
    {
        $map = new Map();

        $this->assertTrue($map->add('web', 'vendor/fi1a/foo/configs/web.php'));
        $this->assertTrue($map->add('web', 'vendor/fi1a/foo/configs/web2.php'));
        $this->assertTrue($map->add('fi1a/bar', new File('vendor/fi1a/bar/configs/package.php')));

        $this->assertCount(2, $map->getGroup('web'));
        $this->assertCount(1, $map->getGroup('fi1a/bar'));
        $this->assertCount(0, $map->getGroup('notExists'));
    }

    /**
     * Добавить файл конфигурации
     */
    public function testAddEmptyGroup(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $map = new Map();
        $map->add('', 'vendor/fi1a/foo/configs/web.php');
    }

    /**
     * Добавить файл конфигурации
     */
    public function testAddFileExists(): void
    {
        $this->expectException(ErrorException::class);

        $map = new Map();
        $this->assertTrue($map->add('web', 'vendor/fi1a/foo/configs/web.php'));
        $this->assertTrue($map->add('fi1a/foo', 'vendor/fi1a/foo/configs/web.php'));
    }

    /**
     * Добавить файл конфигурации
     */
    public function testFromArray(): void
    {
        $map = Map::fromArray([
            [
                'group' => 'web',
                'path' => 'vendor/fi1a/foo/configs/web.php',
            ],
            [
                'group' => 'web',
                'path' => 'vendor/fi1a/foo/configs/web2.php',
            ],
            [
                'group' => 'fi1a/bar',
                'path' => 'vendor/fi1a/bar/configs/package.php',
            ],
        ]);

        $this->assertCount(2, $map->getGroup('web'));
        $this->assertCount(1, $map->getGroup('fi1a/bar'));
        $this->assertCount(0, $map->getGroup('notExists'));
    }
}
