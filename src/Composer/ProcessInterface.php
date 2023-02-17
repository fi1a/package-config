<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Fi1a\PackageConfig\MapInterface;

/**
 * Процесс создания карты файлов конфигурации
 */
interface ProcessInterface
{
    /**
     * Процесс создания карты файлов конфигурации
     */
    public function process(): MapInterface;
}
