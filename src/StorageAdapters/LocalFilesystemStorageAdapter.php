<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\StorageAdapters;

use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;

/**
 * Адаптер хранения карты конфигурационных файлов в локальной файловой системе
 */
class LocalFilesystemStorageAdapter extends FilesystemStorageAdapter
{
    public function __construct(string $path)
    {
        $filesystem = new Filesystem(new LocalAdapter($path));
        parent::__construct($filesystem);
    }
}
