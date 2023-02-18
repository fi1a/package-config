<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Composer\Composer;

/**
 * Сервис
 */
interface ServiceInterface
{
    /**
     * Процесс создания карты файлов конфигурации
     */
    public function process(Composer $composer): void;

    /**
     * Процесс публикации файлов конфигурации в корневом пакете
     *
     * @param string[]    $files
     *
     * @return array<int, string>
     */
    public function publish(Composer $composer, string $packageName, array $files = []): array;
}
