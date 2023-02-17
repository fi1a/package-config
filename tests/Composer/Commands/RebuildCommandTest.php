<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer\Commands;

use Composer\Console\Application;
use Fi1a\PackageConfig\Composer\Commands\RebuildCommand;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RebuildCommandTest extends ComposerTestCase
{
    public function testRebuild(): void
    {
        $this->executeCommand();
        $this->assertMapFile();
    }

    protected function executeCommand(): void
    {
        $command = new RebuildCommand();
        $command->setComposer($this->getComposerMock());
        $command->setIO($this->getIoMock());
        (new Application())->addCommands([$command]);
        (new CommandTester($command))->execute([]);
    }
}
