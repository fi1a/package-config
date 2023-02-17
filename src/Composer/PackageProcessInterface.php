<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

/**
 * Процесс обработчки пакета
 */
interface PackageProcessInterface
{
    /**
     * Возвращает файлы конфигураций
     *
     * @return list<array{group: string, path: string}>
     */
    public function getConfigs(): array;
}
