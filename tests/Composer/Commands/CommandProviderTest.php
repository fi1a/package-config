<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer\Commands;

use Fi1a\PackageConfig\Composer\Commands\CommandProvider;
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
        $this->assertCount(1, $command->getCommands());
    }
}
