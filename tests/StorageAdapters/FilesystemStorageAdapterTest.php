<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\StorageAdapters;

use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;
use Fi1a\PackageConfig\StorageAdapters\FilesystemStorageAdapter;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

/**
 * Адаптер хранения карты конфигурационных файлов в файловой системе
 */
class FilesystemStorageAdapterTest extends ComposerTestCase
{
    /**
     * Чтение и запись
     */
    public function testReadWrite(): void
    {
        $filesystem = new Filesystem(new LocalAdapter(__DIR__ . '/../../runtime/tests'));
        $adapter = new FilesystemStorageAdapter($filesystem);
        $this->assertCount(0, $adapter->read());
        $this->assertTrue($adapter->write([['group' => 'web', 'path' => 'foo/bar/web.php']]));
        $this->assertCount(1, $adapter->read());
    }

    /**
     * Чтение
     */
    public function testReadEmptyFile(): void
    {
        $filesystem = new Filesystem(new LocalAdapter(__DIR__ . '/../../runtime/tests'));
        $filesystem->factoryFile('./.map.json')->write('');
        $adapter = new FilesystemStorageAdapter($filesystem);
        $this->assertCount(0, $adapter->read());
    }

    /**
     * Чтение
     */
    public function testReadJsonError(): void
    {
        $filesystem = new Filesystem(new LocalAdapter(__DIR__ . '/../../runtime/tests'));
        $filesystem->factoryFile('./.map.json')->write('"string"');
        $adapter = new FilesystemStorageAdapter($filesystem);
        $this->assertCount(0, $adapter->read());
    }
}
