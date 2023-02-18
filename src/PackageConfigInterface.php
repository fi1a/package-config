<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use Fi1a\Config\ConfigValuesInterface;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

/**
 * Конфигурация пакетов
 */
interface PackageConfigInterface
{
    /**
     * Возврашает параметры группы
     */
    public function getGroup(string $group): ConfigValuesInterface;

    /**
     * Создает объект конфигурации пакетов
     */
    public static function create(
        StorageAdapterInterface $adapter,
        ?string $rootPath = null
    ): PackageConfigInterface;
}
