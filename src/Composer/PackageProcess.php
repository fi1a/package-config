<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Composer\Composer;
use Composer\Factory;
use Composer\Package\CompletePackageInterface;

/**
 * Процесс обработчки пакета
 */
class PackageProcess implements PackageProcessInterface
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var CompletePackageInterface
     */
    protected $package;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var CompletePackageInterface
     */
    protected $rootPackage;

    public function __construct(Composer $composer, CompletePackageInterface $package)
    {
        $this->composer = $composer;
        $this->package = $package;
        $this->rootPath = realpath(dirname(Factory::getComposerFile()));
        $this->rootPackage = $composer->getPackage();
    }

    /**
     * @inheritDoc
     */
    public function getGroups(): array
    {
        $extra = $this->package->getExtra();

        if (!isset($extra['package-config']) || !is_array($extra['package-config'])) {
            return [];
        }

        $groups = [];
        /**
         * @var string $group
         * @var string $fileName
         */
        foreach ($extra['package-config'] as $group => $fileName) {
            $fileName = ltrim($fileName, '/');
            if (!$group || !$fileName) {
                continue;
            }

            $groups[$group] = $fileName;
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public function getConfigs(): array
    {
        $configs = [];

        foreach ($this->getGroups() as $group => $fileName) {
            $path = null;
            if ($this->package->getName()) {
                $path = $this->rootPath . '/configs/' . $this->package->getName() . '/' . $fileName;
            }

            if (!$path || !is_file($path)) {
                $packagePath = $this->rootPackage === $this->package
                    ? $this->rootPath
                    : $this->composer->getInstallationManager()
                        ->getInstallPath($this->package);
                $path = $packagePath . '/configs/' . $fileName;
            }

            if (mb_stripos($path, $this->rootPath) === 0) {
                $path = mb_substr($path, mb_strlen($this->rootPath));
            }
            $path = ltrim($path, '/');

            $configs[] = [
                'group' => $group,
                'path' => $path,
            ];
        }

        return $configs;
    }
}
