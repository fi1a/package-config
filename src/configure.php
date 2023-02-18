<?php

declare(strict_types=1);

use Fi1a\DI\Builder;
use Fi1a\PackageConfig\PackageConfig;
use Fi1a\PackageConfig\PackageConfigInterface;
use Fi1a\PackageConfig\StorageAdapters\LocalFilesystemStorageAdapter;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

di()->config()->addDefinition(
    Builder::build(StorageAdapterInterface::class)
        ->defineClass(LocalFilesystemStorageAdapter::class)
        ->defineConstructor([__DIR__ . '/../runtime'])
    ->getDefinition()
);

di()->config()->addDefinition(
    Builder::build(PackageConfigInterface::class)
    ->defineFactory(function (StorageAdapterInterface $adapter) {
        static $config;

        if ($config === null) {
            $config = PackageConfig::create($adapter);
        }

        return $config;
    })
    ->getDefinition()
);
