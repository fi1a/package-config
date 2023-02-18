<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

/**
 * Процесс обработчки пакета
 */
interface PackageProcessInterface
{
    /**
     * Возвращает группы и названия файлов конфигураций
     *
     * @return list<array{group: string, file: string, sort: int}>
     */
    public function getGroups(): array;

    /**
     * Возвращает файлы конфигураций
     *
     * @return list<array{group: string, path: string, sort: int}>
     */
    public function getConfigs(): array;
}
