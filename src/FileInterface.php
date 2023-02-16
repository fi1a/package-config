<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

/**
 * Файл
 */
interface FileInterface
{
    /**
     * Возврашает путь до файла
     */
    public function getPath(): string;
}
