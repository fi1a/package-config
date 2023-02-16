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
     * Инициализация из массива
     *
     * @param array<string, array<string, string>> $map
     */
    public static function fromArray(array $map): MapInterface;
}
