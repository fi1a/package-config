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
     */
    public function add(string $group, FileInterface $file): bool;

    /**
     * Возвращает файлы конфигурации для группы
     */
    public function getGroup(string $group): FileCollectionInterface;

    /**
     * Добавить файлы конфигурации из массива
     *
     * @param list<array{group: string, path: string, sort: int}> $map
     */
    public function addArray(array $map): bool;

    /**
     * Файлы конфигурации в массив
     *
     * @return list<array{group: string, path: string, sort: int}>
     */
    public function toArray(): array;

    /**
     * Инициализация из массива
     *
     * @param list<array{group: string, path: string, sort: int}> $map
     */
    public static function createFromArray(array $map): MapInterface;
}
