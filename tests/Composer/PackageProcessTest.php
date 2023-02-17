<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer;

use Composer\Package\CompletePackage;
use Fi1a\PackageConfig\Composer\PackageProcess;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

use const JSON_THROW_ON_ERROR;

/**
 * Процесс обработчки пакета
 */
class PackageProcessTest extends ComposerTestCase
{
    /**
     * Процесс обработчки пакета
     */
    public function testGetConfigs()
    {
        $package = new CompletePackage('foo/bar', '1.0.0', '1.0.0');
        $package->setExtra(json_decode(
            file_get_contents(__DIR__ . '/../Fixtures/packages/vendor/foo/bar/composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['extra']);
        $packageProcess = new PackageProcess($this->getComposerMock(), $package);
        $configs = $packageProcess->getConfigs();
        $this->assertCount(4, $configs);
    }

    /**
     * Процесс обработчки пакета
     */
    public function testGetConfigsEmptyExtra()
    {
        $package = new CompletePackage('foo/bar', '1.0.0', '1.0.0');
        $package->setExtra([]);
        $packageProcess = new PackageProcess($this->getComposerMock(), $package);
        $configs = $packageProcess->getConfigs();
        $this->assertCount(0, $configs);
    }

    /**
     * Процесс обработчки пакета
     */
    public function testGetConfigsRootPackage()
    {
        $composer = $this->getComposerMock();
        $packageProcess = new PackageProcess($composer, $composer->getPackage());
        $configs = $packageProcess->getConfigs();
        $this->assertCount(3, $configs);
    }
}
