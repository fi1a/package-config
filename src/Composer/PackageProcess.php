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

    /**
     * @var int
     */
    protected $defaultSort;

    public function __construct(Composer $composer, CompletePackageInterface $package, int $defaultSort = 500)
    {
        $this->composer = $composer;
        $this->package = $package;
        $this->rootPath = realpath(dirname(Factory::getComposerFile()));
        $this->rootPackage = $composer->getPackage();
        $this->defaultSort = $defaultSort;
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
         * @var mixed $values
         */
        foreach ($extra['package-config'] as $group => $values) {
            if (!$group) {
                continue;
            }

            if (!is_array($values)) {
                $values = [
                    [
                        'file' => $values,
                    ],
                ];
            }

            /** @var array{file: mixed|null, sort: mixed|null} $value */
            foreach ($values as $value) {
                if (!isset($value['file'])) {
                    continue;
                }

                $fileName = ltrim((string) $value['file'], '/');

                if (!$fileName) {
                    continue;
                }

                $groups[] = [
                    'group' => $group,
                    'file' => $fileName,
                    'sort' => isset($value['sort']) ? (int) $value['sort'] : $this->defaultSort,
                ];
            }
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public function getConfigs(): array
    {
        $configs = [];

        foreach ($this->getGroups() as $value) {
            $path = null;
            if ($this->package->getName()) {
                $path = $this->rootPath . '/configs/' . $this->package->getName() . '/' . $value['file'];
            }

            if (!$path || !is_file($path)) {
                $packagePath = $this->rootPackage === $this->package
                    ? $this->rootPath
                    : $this->composer->getInstallationManager()
                        ->getInstallPath($this->package);
                $path = $packagePath . '/configs/' . $value['file'];
            }

            if (mb_stripos($path, $this->rootPath) === 0) {
                $path = mb_substr($path, mb_strlen($this->rootPath));
            }
            $path = ltrim($path, '/');

            $configs[] = [
                'group' => $value['group'],
                'path' => $path,
                'sort' => $value['sort'],
            ];
        }

        return $configs;
    }
}
