<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer\Commands;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Fi1a\PackageConfig\Composer\Process;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Выполняет обход всех установленных пакетов и обновляет карту файлов конфигурации
 */
class RebuildCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('package-config-rebuild')
            ->setDescription('Выполняет обход всех установленных пакетов и обновляет карту файлов конфигурации')
            ->setHelp('Выполняет обход всех установленных пакетов и обновляет карту файлов конфигурации');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Composer $composer */
        $composer = $this->tryComposer();
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $process = new Process($composer);
        $adapter->write($process->process()->toArray());

        return 0;
    }
}
