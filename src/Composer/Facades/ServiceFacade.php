<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer\Facades;

use Composer\Composer;
use Fi1a\Facade\AbstractFacade;
use Fi1a\PackageConfig\Composer\Service;

/**
 * Сервис
 *
 * @method static void process(Composer $composer)
 * @method static string[] publish(Composer $composer, string $packageName, array $files = [])
 */
class ServiceFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function factory(): object
    {
        return new Service();
    }
}
