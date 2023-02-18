<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer;

use Fi1a\PackageConfig\Composer\Exceptions\CopyErrorException;
use Fi1a\PackageConfig\Composer\Exceptions\PackageNotFoundException;
use Fi1a\PackageConfig\Composer\Facades\ServiceFacade;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

/**
 * Сервис
 */
class ServiceTest extends ComposerTestCase
{
    /**
     * Процесс публикации файлов конфигурации в корневом пакете
     */
    public function testPublish()
    {
        $this->assertCount(
            1,
            ServiceFacade::publish($this->getComposerMock(), 'foo/bar')
        );
        $this->assertPublishedFooBarMapFile();
    }

    /**
     * Процесс публикации файлов конфигурации в корневом пакете
     */
    public function testPublishPackageNotFound(): void
    {
        $this->expectException(PackageNotFoundException::class);
        ServiceFacade::publish($this->getComposerMock(), 'foo/not-exists');
    }

    /**
     * Процесс публикации файлов конфигурации в корневом пакете
     */
    public function testPublishPackageCopyError(): void
    {
        $this->expectException(CopyErrorException::class);
        chmod($this->testDirectory . '/configs', 0000);
        try {
            ServiceFacade::publish($this->getComposerMock(), 'foo/bar');
        } catch (CopyErrorException $exception) {
            chmod($this->testDirectory . '/configs', 0777);

            throw $exception;
        }
    }

    /**
     * Процесс создания карты файлов конфигурации
     */
    public function testProcess()
    {
        ServiceFacade::process($this->getComposerMock());
        $this->assertMapFile();
    }
}
