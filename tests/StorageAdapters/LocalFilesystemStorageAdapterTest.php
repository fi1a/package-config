<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\StorageAdapters;

use Fi1a\PackageConfig\StorageAdapters\LocalFilesystemStorageAdapter;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

/**
 * Адаптер хранения карты конфигурационных файлов в локальной файловой системе
 */
class LocalFilesystemStorageAdapterTest extends ComposerTestCase
{
    /**
     * Чтение и запись
     */
    public function testReadWrite(): void
    {
        $adapter = new LocalFilesystemStorageAdapter(__DIR__ . '/../../runtime/tests');
        $this->assertCount(0, $adapter->read());
        $this->assertTrue($adapter->write([['group' => 'web', 'path' => 'foo/bar/web.php']]));
        $this->assertCount(1, $adapter->read());
    }
}
