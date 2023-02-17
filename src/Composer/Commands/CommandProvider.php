<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer\Commands;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;

/**
 * Команды
 */
class CommandProvider implements ComposerCommandProvider
{
    /**
     * @inheritDoc
     */
    public function getCommands(): array
    {
        return [
            new RebuildCommand(),
        ];
    }
}
