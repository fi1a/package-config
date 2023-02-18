<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use Fi1a\Config\Config;
use Fi1a\Config\ConfigValues;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Parsers\ParserInterface;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\FilesystemInterface;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

/**
 * Конфигурация пакетов
 */
class PackageConfig implements PackageConfigInterface
{
    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var array<string, ConfigValuesInterface>
     */
    protected $cache = [];

    /**
     * @var ParserInterface
     */
    protected $parser;

    public function __construct(MapInterface $map, ?string $rootPath = null)
    {
        if ($rootPath === null) {
            $rootPath = __DIR__ . '/../../../..';
            if (!is_file($rootPath . '/composer.lock')) {
                $rootPath = __DIR__ . '/..';
            }
        }
        $this->map = $map;
        $this->filesystem = new Filesystem(new LocalAdapter($rootPath));
        $this->parser = new PHPParser();
    }

    /**
     * @inheritDoc
     */
    public function getGroup(string $group): ConfigValuesInterface
    {
        if (isset($this->cache[$group])) {
            return $this->cache[$group];
        }

        $configValues = new ConfigValues();

        $groupFiles = $this->map->getGroup($group);
        if (!$groupFiles->count()) {
            return $this->cache[$group] = $configValues;
        }

        $batch = [];
        /** @var FileInterface $file */
        foreach ($groupFiles as $file) {
            $batch[] = [
                new FileReader($this->filesystem->factoryFile($file->getPath())),
                $this->parser,
            ];
        }

        return $this->cache[$group] = Config::batchLoad($batch);
    }

    /**
     * @inheritDoc
     */
    public static function create(
        StorageAdapterInterface $adapter,
        ?string $rootPath = null
    ): PackageConfigInterface {
        return new PackageConfig(Map::createFromArray($adapter->read()), $rootPath);
    }
}
