<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer\Commands;

use Composer\Console\Application;
use Fi1a\PackageConfig\Composer\Commands\PublishCommand;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Выполняет публикацию конфигурационных файлов в директории корневого пакета
 */
class PublishCommandTest extends ComposerTestCase
{
    /**
     * Выполнение команды
     */
    public function testPublish(): void
    {
        $this->executeCommand(['package' => 'foo/bar']);
        $this->assertPublishedFooBarMapFile();
    }

    /**
     * Выполнение команды
     */
    public function testPublishPackageNotFound(): void
    {
        $this->assertEquals(1, $this->executeCommand(['package' => 'foo/not-exists']));
    }

    /**
     * Выполнение команды
     */
    public function testPublishPackageCopyError(): void
    {
        chmod($this->testDirectory . '/configs', 0000);
        $this->assertEquals(1, $this->executeCommand(['package' => 'foo/bar']));
        chmod($this->testDirectory . '/configs', 0775);
    }

    /**
     * Выполнить команду
     *
     * @param array<int, string> $input
     */
    protected function executeCommand(array $input): int
    {
        $command = new PublishCommand();
        $command->setComposer($this->getComposerMock());
        $command->setIO($this->getIoMock());
        (new Application())->addCommands([$command]);

        return (new CommandTester($command))->execute($input);
    }
}
