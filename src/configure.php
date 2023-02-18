<?php

declare(strict_types=1);

use Fi1a\DI\Builder;
use Fi1a\PackageConfig\StorageAdapters\LocalFilesystemStorageAdapter;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

di()->config()->addDefinition(
    Builder::build(StorageAdapterInterface::class)
        ->defineClass(LocalFilesystemStorageAdapter::class)
        ->defineConstructor([__DIR__ . '/../runtime'])
    ->getDefinition()
);
