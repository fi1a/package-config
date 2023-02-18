<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer\Commands;

use Composer\Command\BaseCommand;
use Fi1a\PackageConfig\Composer\Facades\ServiceFacade;
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
        ServiceFacade::process($this->requireComposer());
        $output->writeln('<fg=green>Карта файлов конфигурации обновлена</>');

        return 0;
    }
}
