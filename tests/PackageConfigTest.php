<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\Config\ConfigValuesInterface;
use Fi1a\PackageConfig\Composer\Facades\ServiceFacade;
use Fi1a\PackageConfig\Map;
use Fi1a\PackageConfig\PackageConfig;
use Fi1a\PackageConfig\PackageConfigInterface;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

/**
 * Конфигурация пакетов
 */
class PackageConfigTest extends ComposerTestCase
{
    protected function getPackageConfig(): PackageConfigInterface
    {
        $map = Map::createFromArray([
            [
                'group' => 'params',
                'path' => 'tests/Fixtures/packages/vendor/foo/bar/configs/params.php',
            ],
            [
                'group' => 'web',
                'path' => 'tests/Fixtures/packages/vendor/foo/bar/configs/web.php',
            ],
            [
                'group' => 'duplicate1',
                'path' => 'tests/Fixtures/packages/vendor/foo/bar/configs/duplicate.php',
            ],
            [
                'group' => 'foo/dev',
                'path' => 'tests/Fixtures/packages/vendor/foo/dev/configs/package.php',
            ],
            [
                'group' => 'params',
                'path' => 'tests/Fixtures/packages/configs/params.php',
            ],
        ]);

        return new PackageConfig($map);
    }

    /**
     * Возврашает параметры группы
     */
    public function testGetGroup(): void
    {
        $packageConfig = $this->getPackageConfig();
        $this->assertInstanceOf(ConfigValuesInterface::class, $packageConfig->getGroup('params'));
        $this->assertCount(1, $packageConfig->getGroup('params'));
        $this->assertEquals(
            [
                'foo' => [
                    'bar', 'baz',
                ],
            ],
            $packageConfig->getGroup('params')->getArrayCopy()
        );
    }

    /**
     * Возврашает параметры группы
     */
    public function testGetGroupNotExists(): void
    {
        $packageConfig = $this->getPackageConfig();
        $this->assertInstanceOf(ConfigValuesInterface::class, $packageConfig->getGroup('not-exists'));
        $this->assertCount(0, $packageConfig->getGroup('not-exists'));
    }

    /**
     * Создает объект конфигурации пакетов
     */
    public function testCreateFromMapFile(): void
    {
        ServiceFacade::process($this->getComposerMock());
        $this->assertMapFile();
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $packageConfig = PackageConfig::create($adapter, $this->testDirectory);
        $this->assertInstanceOf(ConfigValuesInterface::class, $packageConfig->getGroup('params'));
        $this->assertCount(1, $packageConfig->getGroup('params'));
        $this->assertEquals(
            [
                'foo' => [
                    'bar', 'baz',
                ],
            ],
            $packageConfig->getGroup('params')->getArrayCopy()
        );
    }
}
