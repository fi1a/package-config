<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\StorageAdapters;

/**
 * Адаптер хранения карты конфигурационных файлов
 */
interface StorageAdapterInterface
{
    /**
     * Запись
     *
     * @param list<array{group: string, path: string}> $map
     */
    public function write(array $map): bool;

    /**
     * Чтение
     *
     * @return list<array{group: string, path: string}>
     */
    public function read(): array;
}
