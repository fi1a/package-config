<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

/**
 * Конфигурационные файлы
 */
interface MapInterface
{
    /**
     * Добавить файл конфигурации
     *
     * @param string|FileInterface $path
     */
    public function add(string $group, $path): bool;

    /**
     * Возвращает файлы конфигурации для группы
     */
    public function getGroup(string $group): FileCollectionInterface;

    /**
     * Добавить файлы конфигурации из массива
     *
     * @param list<array{group: string, path: string}> $map
     */
    public function addArray(array $map): bool;

    /**
     * Инициализация из массива
     *
     * @param list<array{group: string, path: string}> $map
     */
    public static function createFromArray(array $map): MapInterface;
}
