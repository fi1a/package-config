<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\StorageAdapters;

use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\FilesystemInterface;

use const JSON_UNESCAPED_UNICODE;

/**
 * Адаптер хранения карты конфигурационных файлов в файловой системе
 */
class FilesystemStorageAdapter implements StorageAdapterInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var FileInterface
     */
    protected $file;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->file = $this->filesystem->factoryFile('./.map.json');
    }

    /**
     * @inheritDoc
     */
    public function write(array $map): bool
    {
        return $this->file->write(json_encode($map, JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (!$this->file->isExist()) {
            return [];
        }

        $json = $this->file->read();

        if (!$json) {
            return [];
        }

        /** @var list<array{group: string, path: string}>|null $map */
        $map = json_decode($json, true);

        if (!is_array($map)) {
            return [];
        }

        return $map;
    }
}
