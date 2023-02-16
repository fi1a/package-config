<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer\Command;

use Fi1a\PackageConfig\Composer\Command\CommandProvider;
use PHPUnit\Framework\TestCase;

/**
 * Команды
 */
class CommandProviderTest extends TestCase
{
    /**
     * Команды
     */
    public function testGetCommands(): void
    {
        $command = new CommandProvider();
        $this->assertEquals([], $command->getCommands());
    }
}
