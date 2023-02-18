<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\TestCases;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\IOInterface;
use Composer\Installer\InstallationManager;
use Composer\Package\CompletePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Repository\RepositoryManager;
use Composer\Semver\Constraint\Constraint;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\NodeInterface;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use const JSON_THROW_ON_ERROR;

class ComposerTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $packagesDirectory;

    /**
     * @var string
     */
    protected $testDirectory;

    protected $filesystem;

    /**
     * @inheritDoc
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->packagesDirectory = realpath(__DIR__ . '/../Fixtures/packages/vendor');
        $this->testDirectory =  realpath(__DIR__ . '/../../runtime/tests');

        $this->filesystem = new Filesystem(new LocalAdapter(__DIR__ . '/../..'));
    }

    /**
     * @return IOInterface|MockObject
     */
    protected function getIoMock()
    {
        return $this->getMockBuilder(IOInterface::class)
            ->getMockForAbstractClass();
    }

    protected function setUp(): void
    {
        $this->filesystem->factoryFolder(__DIR__ . '/../Fixtures/packages')
            ->copy(__DIR__ . '/../../runtime/tests');
        putenv("COMPOSER=$this->testDirectory/composer.json");

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        /** @var NodeInterface $node */
        foreach ($this->filesystem->factoryFolder($this->testDirectory)->all() as $node) {
            if ($node->getName() === '.gitkeep') {
                continue;
            }
            $node->delete();
        }
    }

    /**
     * @return Composer|MockObject
     */
    protected function getComposerMock()
    {
        $sourcePath = $this->packagesDirectory;
        $targetPath = $this->testDirectory . '/vendor';

        $extra = [
            'package-config' => [
                'params' => 'params.php',
                'web' => 'web.php',
                'duplicate1' => 'duplicate.php',
                'duplicate2' => 'duplicate.php',
            ],
        ];

        $config = $this->createMock(Config::class);
        $config->method('get')
            ->willReturn($targetPath);

        $rootPackage = $this->getMockBuilder(RootPackageInterface::class)
            ->onlyMethods(['getRequires', 'getDevRequires', 'getExtra', 'getName'])
            ->getMockForAbstractClass();
        $rootPackage->method('getRequires')
            ->willReturn([
                'foo/bar' => new Link(
                    $sourcePath . '/foo/bar',
                    $targetPath . '/foo/bar',
                    new Constraint('>=', '1.0.0')
                ),
                'foo/baz' => new Link(
                    $sourcePath . '/foo/baz',
                    $targetPath . '/foo/baz',
                    new Constraint('>=', '1.0.0')
                ),
                'foo/qux' => new Link(
                    $sourcePath . '/foo/qux',
                    $targetPath . '/foo/qux',
                    new Constraint('>=', '1.0.0')
                ),
            ]);
        $rootPackage->method('getDevRequires')
            ->willReturn([
                'foo/dev' => new Link(
                    $sourcePath . '/foo/dev',
                    $targetPath . '/foo/dev',
                    new Constraint('>=', '1.0.0')
                ),
            ]);
        $rootPackage->method('getExtra')
            ->willReturn($extra);
        $rootPackage->method('getName')
            ->willReturn('foo/root');

        $packages = [
            new CompletePackage('foo/bar', '1.0.0', '1.0.0'),
            new CompletePackage('foo/dev', '1.0.0', '1.0.0'),
            new CompletePackage('foo/qux', '1.0.0', '1.0.0'),
            new Package('foo/baz', '1.0.0', '1.0.0'),
        ];

        foreach ($packages as $package) {
            $path = $sourcePath . '/' . $package->getName() . '/composer.json';
            if (is_file($path)) {
                $package->setExtra(json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR)['extra']);
            }
        }

        $repository = $this->getMockBuilder(InstalledRepositoryInterface::class)
            ->onlyMethods(['getPackages'])
            ->getMockForAbstractClass();
        $repository
            ->method('getPackages')
            ->willReturn($packages);

        $repositoryManager = $this->getMockBuilder(RepositoryManager::class)
            ->onlyMethods(['getLocalRepository'])
            ->disableOriginalConstructor()
            ->getMock();
        $repositoryManager->method('getLocalRepository')
            ->willReturn($repository);

        $installationManager = $this->getMockBuilder(InstallationManager::class)
            ->onlyMethods(['getInstallPath'])
            ->disableOriginalConstructor()
            ->getMock();
        $installationManager->method('getInstallPath')
            ->willReturnCallback(
                static function (PackageInterface $package) use ($targetPath) {
                    return $targetPath . '/' . $package->getName();
                }
            );

        $eventDispatcher = $this->getMockBuilder(EventDispatcher::class)
            ->onlyMethods(['dispatch'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventDispatcher->method('dispatch')
            ->willReturn(0);

        $composer = $this->getMockBuilder(Composer::class)
            ->onlyMethods([
                'getConfig',
                'getPackage',
                'getRepositoryManager',
                'getInstallationManager',
                'getEventDispatcher',
            ])
            ->getMock();

        $composer->method('getConfig')
            ->willReturn($config);
        $composer->method('getPackage')
            ->willReturn($rootPackage);
        $composer->method('getRepositoryManager')
            ->willReturn($repositoryManager);
        $composer->method('getInstallationManager')
            ->willReturn($installationManager);
        $composer->method('getEventDispatcher')
            ->willReturn($eventDispatcher);

        return $composer;
    }

    /**
     * Проверка созданной карты файлов после публикауии
     */
    protected function assertPublishedFooBarMapFile(): void
    {
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $map = $adapter->read();

        $this->assertEquals([
            [
                'group' => 'params',
                'path' => 'configs/params.php',
                'sort' => 1000,
            ],
            [
                'group' => 'params',
                'path' => 'configs/foo/bar/params.php',
                'sort' => 500,
            ],
            [
                'group' => 'web',
                'path' => 'configs/web.php',
                'sort' => 1000,
            ],
            [
                'group' => 'web',
                'path' => 'configs/foo/bar/web.php',
                'sort' => 500,
            ],
            [
                'group' => 'duplicate1',
                'path' => 'configs/duplicate.php',
                'sort' => 1000,
            ],
            [
                'group' => 'duplicate1',
                'path' => 'vendor/foo/bar/configs/duplicate.php',
                'sort' => 500,
            ],
            [
                'group' => 'foo/dev',
                'path' => 'vendor/foo/dev/configs/package.php',
                'sort' => 500,
            ],
        ], $map);
    }

    /**
     * Проверка созданной карты файлов
     */
    protected function assertMapFile(): void
    {
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $map = $adapter->read();

        $this->assertEquals([
            [
                'group' => 'params',
                'path' => 'configs/params.php',
                'sort' => 1000,
            ],
            [
                'group' => 'params',
                'path' => 'configs/foo/bar/params.php',
                'sort' => 500,
            ],
            [
                'group' => 'web',
                'path' => 'configs/web.php',
                'sort' => 1000,
            ],
            [
                'group' => 'web',
                'path' => 'vendor/foo/bar/configs/web.php',
                'sort' => 500,
            ],
            [
                'group' => 'duplicate1',
                'path' => 'configs/duplicate.php',
                'sort' => 1000,
            ],
            [
                'group' => 'duplicate1',
                'path' => 'vendor/foo/bar/configs/duplicate.php',
                'sort' => 500,
            ],
            [
                'group' => 'foo/dev',
                'path' => 'vendor/foo/dev/configs/package.php',
                'sort' => 500,
            ],
        ], $map);
    }
}
