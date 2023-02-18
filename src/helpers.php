<?php

declare(strict_types=1);

use Fi1a\Config\ConfigValuesInterface;
use Fi1a\PackageConfig\PackageConfigInterface;

/**
 * Возвращает значения группы конфигураций пакетов
 */
function config(string $group): ConfigValuesInterface
{
    /** @var PackageConfigInterface $config */
    $config = di()->get(PackageConfigInterface::class);

    return $config->getGroup($group);
}
