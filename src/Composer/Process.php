<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Composer\Composer;
use Composer\Package\CompletePackageInterface;
use ErrorException;
use Fi1a\PackageConfig\Map;
use Fi1a\PackageConfig\MapInterface;

/**
 * Процесс создания карты файлов конфигурации
 */
class Process implements ProcessInterface
{
    /**
     * @var Composer
     */
    protected $composer;

    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * @inheritDoc
     */
    public function process(): MapInterface
    {
        $map = new Map();

        $packages = $this->composer->getRepositoryManager()
            ->getLocalRepository()
            ->getPackages();

        $process = new PackageProcess($this->composer, $this->composer->getPackage(), 1000);

        try {
            $map->addArray($process->getConfigs());
        } catch (ErrorException $exception) {
        }

        foreach ($packages as $package) {
            if (!$package instanceof CompletePackageInterface) {
                continue;
            }

            $process = new PackageProcess($this->composer, $package, 500);

            try {
                $map->addArray($process->getConfigs());
            } catch (ErrorException $exception) {
                continue;
            }
        }

        return $map;
    }
}
