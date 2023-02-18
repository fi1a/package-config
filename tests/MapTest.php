<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use ErrorException;
use Fi1a\PackageConfig\File;
use Fi1a\PackageConfig\FileInterface;
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

        $this->assertTrue($map->add('web', new File('vendor/fi1a/foo/configs/web.php', 500)));
        $this->assertTrue($map->add('web', new File('vendor/fi1a/foo/configs/web2.php', 500)));
        $this->assertTrue($map->add('fi1a/bar', new File('vendor/fi1a/bar/configs/package.php', 500)));

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
        $map->add('', new File('vendor/fi1a/foo/configs/web.php', 500));
    }

    /**
     * Добавить файл конфигурации
     */
    public function testAddFileExists(): void
    {
        $this->expectException(ErrorException::class);

        $map = new Map();
        $this->assertTrue($map->add('web', new File('vendor/fi1a/foo/configs/web.php', 500)));
        $this->assertTrue($map->add('fi1a/foo', new File('vendor/fi1a/foo/configs/web.php', 500)));
    }

    /**
     * Добавить файл конфигурации
     */
    public function testFromArray(): void
    {
        $map = Map::createFromArray([
            [
                'group' => 'web',
                'path' => 'vendor/fi1a/foo/configs/web.php',
                'sort' => 500,
            ],
            [
                'group' => 'web',
                'path' => 'vendor/fi1a/foo/configs/web2.php',
                'sort' => 1000,
            ],
            [
                'group' => 'fi1a/bar',
                'path' => 'vendor/fi1a/bar/configs/package.php',
                'sort' => 500,
            ],
        ]);

        /** @var FileInterface[] $web */
        $web = $map->getGroup('web');
        $this->assertCount(2, $web);
        $this->assertEquals(500, $web[0]->getSort());
        $this->assertEquals(1000, $web[1]->getSort());
        $this->assertCount(1, $map->getGroup('fi1a/bar'));
        $this->assertCount(0, $map->getGroup('notExists'));
    }

    /**
     * Файлы конфигурации в массив
     */
    public function testToArray(): void
    {
        $map = new Map();

        $this->assertTrue($map->add('web', new File('vendor/fi1a/foo/configs/web.php', 500)));
        $this->assertTrue($map->add('web', new File('vendor/fi1a/foo/configs/web2.php', 1000)));
        $this->assertTrue($map->add('fi1a/bar', new File('vendor/fi1a/bar/configs/package.php', 500)));

        $this->assertCount(3, $map->toArray());
        $this->assertEquals(
            [
                [
                    'group' => 'web',
                    'path' => 'vendor/fi1a/foo/configs/web.php',
                    'sort' => 500,
                ],
                [
                    'group' => 'web',
                    'path' => 'vendor/fi1a/foo/configs/web2.php',
                    'sort' => 1000,
                ],
                [
                    'group' => 'fi1a/bar',
                    'path' => 'vendor/fi1a/bar/configs/package.php',
                    'sort' => 500,
                ],
            ],
            $map->toArray()
        );
    }
}
