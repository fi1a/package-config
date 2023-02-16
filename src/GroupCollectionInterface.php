<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use Fi1a\Collection\InstanceCollectionInterface;

/**
 * Коллекция групп
 */
interface GroupCollectionInterface extends InstanceCollectionInterface
{
    /**
     * Проверяет наличие файла с таким путем
     */
    public function isPathExists(string $path): bool;
}
