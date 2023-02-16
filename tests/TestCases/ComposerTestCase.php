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
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Repository\RepositoryManager;
use Composer\Semver\Constraint\Constraint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
    protected $tempDirectory;

    /**
     * @var string
     */
    protected $tempConfigDirectory;

    /**
     * @inheritDoc
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->packagesDirectory = __DIR__ . '/../Fixtures/packages';
        $this->tempDirectory = __DIR__ . '/../../runtime/tests';
        $this->tempConfigDirectory = "$this->tempDirectory/config";
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
        mkdir($this->tempConfigDirectory);
        putenv("COMPOSER=$this->tempDirectory/composer.json");
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $directoryIterator = new RecursiveDirectoryIterator(
            $this->tempDirectory,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $filesIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($filesIterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());

                continue;
            }

            unlink($file->getRealPath());
        }
    }

    /**
     * @return Composer|MockObject
     */
    protected function getComposerMock()
    {
        $rootPath = $this->tempDirectory;
        $sourcePath = $this->packagesDirectory;
        $targetPath = "$this->tempDirectory/vendor";

        $extra = [];

        $config = $this->createMock(Config::class);
        $config
            ->method('get')
            ->willReturn(dirname(__DIR__, 2) . '/vendor');

        $rootPackage = $this
            ->getMockBuilder(RootPackageInterface::class)
            ->onlyMethods(['getRequires', 'getDevRequires', 'getExtra'])
            ->getMockForAbstractClass();
        $rootPackage
            ->method('getRequires')
            ->willReturn([
                'foo/bar' => new Link("$sourcePath/foo/bar", "$targetPath/foo/bar", new Constraint('>=', '1.0.0')),
            ]);
        $rootPackage
            ->method('getDevRequires')
            ->willReturn([
                'foo/dev' => new Link("$sourcePath/foo/dev", "$targetPath/foo/dev", new Constraint('>=', '1.0.0')),
            ]);
        $rootPackage
            ->method('getExtra')
            ->willReturn($extra);

        $packages = [
            new CompletePackage('foo/bar', '1.0.0', '1.0.0'),
            new CompletePackage('foo/dev', '1.0.0', '1.0.0'),
        ];

        foreach ($packages as $package) {
            $path = "$sourcePath/{$package->getName()}" . '/composer.json';
            $package->setExtra(json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR)['extra']);
        }

        $repository = $this
            ->getMockBuilder(InstalledRepositoryInterface::class)
            ->onlyMethods(['getPackages'])
            ->getMockForAbstractClass();
        $repository
            ->method('getPackages')
            ->willReturn($packages);

        $repositoryManager = $this
            ->getMockBuilder(RepositoryManager::class)
            ->onlyMethods(['getLocalRepository'])
            ->disableOriginalConstructor()
            ->getMock();
        $repositoryManager
            ->method('getLocalRepository')
            ->willReturn($repository);

        $installationManager = $this
            ->getMockBuilder(InstallationManager::class)
            ->onlyMethods(['getInstallPath'])
            ->disableOriginalConstructor()
            ->getMock();
        $installationManager
            ->method('getInstallPath')
            ->willReturnCallback(
                static function (PackageInterface $package) use ($sourcePath, $rootPath) {
                    if ($package instanceof RootPackageInterface) {
                        return $rootPath;
                    }

                    return str_replace('test/', '', "$sourcePath/{$package->getName()}");
                }
            );

        $eventDispatcher = $this
            ->getMockBuilder(EventDispatcher::class)
            ->onlyMethods(['dispatch'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventDispatcher
            ->method('dispatch')
            ->willReturn(0);

        $composer = $this
            ->getMockBuilder(Composer::class)
            ->onlyMethods([
                'getConfig',
                'getPackage',
                'getRepositoryManager',
                'getInstallationManager',
                'getEventDispatcher',
            ])
            ->getMock();

        $composer
            ->method('getConfig')
            ->willReturn($config);
        $composer
            ->method('getPackage')
            ->willReturn($rootPackage);
        $composer
            ->method('getRepositoryManager')
            ->willReturn($repositoryManager);
        $composer
            ->method('getInstallationManager')
            ->willReturn($installationManager);
        $composer
            ->method('getEventDispatcher')
            ->willReturn($eventDispatcher);

        return $composer;
    }

    /**
     * Проверка созданной карты файлов
     */
    protected function assertMapFile(): void
    {
        $this->assertTrue(true);
    }
}
