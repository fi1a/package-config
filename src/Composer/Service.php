<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Composer\Composer;
use Composer\Factory;
use Composer\Package\CompletePackageInterface;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;
use Fi1a\PackageConfig\Composer\Exceptions\CopyErrorException;
use Fi1a\PackageConfig\Composer\Exceptions\PackageNotFoundException;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

/**
 * Сервис
 */
class Service implements ServiceInterface
{
    /**
     * @inheritDoc
     */
    public function process(Composer $composer): void
    {
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $process = new Process($composer);
        $adapter->write($process->process()->toArray());
    }

    /**
     * @inheritDoc
     */
    public function publish(Composer $composer, string $packageName, array $files = []): array
    {
        $package = null;

        $packages = $composer->getRepositoryManager()
            ->getLocalRepository()
            ->getPackages();

        foreach ($packages as $item) {
            if ($item->getName() === $packageName && $item instanceof CompletePackageInterface) {
                $package = $item;

                break;
            }
        }

        if (!$package) {
            throw new PackageNotFoundException(sprintf('Пакет "%s" не найден', $packageName));
        }

        $rootPackagePath = realpath(dirname(Factory::getComposerFile()));
        $packagePath = $composer->getInstallationManager()
            ->getInstallPath($package);

        $filesystem = new Filesystem(new LocalAdapter($rootPackagePath));

        $packageProcess = new PackageProcess($composer, $package);
        $groups = $packageProcess->getGroups();
        if (!count($files)) {
            foreach ($groups as $fileName) {
                $files[] = $fileName;
            }
        }

        $published = [];

        foreach ($files as $fileName) {
            foreach ($groups as $groupFileName) {
                if ($groupFileName !== $fileName) {
                    continue;
                }
                $rootFilePath = $rootPackagePath . '/configs/' . $package->getName() . '/' . $fileName;
                $file = $filesystem->factoryFile($packagePath . '/configs/' . $groupFileName);
                if (!$file->isExist() || $filesystem->isFileExist($rootFilePath)) {
                    continue;
                }

                if ($file->copy($rootFilePath) === false) {
                    throw new CopyErrorException(
                        sprintf('Не удалось скопировать файл %s', $rootFilePath)
                    );
                }

                $published[] = $rootFilePath;
            }
        }

        $this->process($composer);

        return $published;
    }
}
