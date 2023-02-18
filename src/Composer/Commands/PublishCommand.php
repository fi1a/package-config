<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer\Commands;

use Composer\Command\BaseCommand;
use Fi1a\PackageConfig\Composer\Exceptions\ServiceErrorException;
use Fi1a\PackageConfig\Composer\Facades\ServiceFacade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Выполняет публикацию конфигурационных файлов в директории корневого пакета
 */
class PublishCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('package-config-publish')
            ->setDescription('Выполняет публикацию конфигурационных файлов в директории корневого пакета')
            ->setHelp('Выполняет публикацию конфигурационных файлов в директории корневого пакета')
            ->addArgument('package', InputArgument::REQUIRED, 'Package')
            ->addArgument('files', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Files');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $packageName */
        $packageName = $input->getArgument('package');
        /** @var string[] $fileNames */
        $fileNames = $input->getArgument('files');

        try {
            $published = ServiceFacade::publish($this->requireComposer(), $packageName, $fileNames);
        } catch (ServiceErrorException $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');

            return 1;
        }
        $output->writeln(
            count($published)
                ? '<fg=green>Файлы конфигураций опубликованы</>'
                : 'Нет файлов конфигураций для опубликования'
        );
        foreach ($published as $filePath) {
            $output->writeln($filePath);
        }

        return 0;
    }
}
