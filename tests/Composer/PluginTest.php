<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig\Composer;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Fi1a\PackageConfig\Composer\Commands\CommandProvider;
use Fi1a\PackageConfig\Composer\Plugin;
use Fi1a\Unit\PackageConfig\TestCases\ComposerTestCase;

/**
 * Плагин
 */
class PluginTest extends ComposerTestCase
{
    /**
     * Подписка на события
     */
    public function testGetSubscribedEvents(): void
    {
        $this->assertEquals(
            [
                ScriptEvents::POST_INSTALL_CMD => 'onInstall',
                ScriptEvents::POST_UPDATE_CMD => 'onUpdate',
            ],
            Plugin::getSubscribedEvents(),
        );
    }

    /**
     * Провайдер команд
     */
    public function testGetCapabilities(): void
    {
        $this->assertEquals(
            [ComposerCommandProvider::class => CommandProvider::class],
            (new Plugin())->getCapabilities(),
        );
    }

    /**
     * Обработчик события установки
     */
    public function testOnInstall(): void
    {
        $event = new Event(ScriptEvents::POST_INSTALL_CMD, $this->getComposerMock(), $this->getIoMock());
        $plugin = new Plugin();
        $plugin->onInstall($event);
        $this->assertMapFile();
    }

    /**
     * Обработчик события обновления
     */
    public function testOnUpdate(): void
    {
        $event = new Event(ScriptEvents::POST_UPDATE_CMD, $this->getComposerMock(), $this->getIoMock());
        $plugin = new Plugin();
        $plugin->onUpdate($event);
        $this->assertMapFile();
    }

    /**
     * Методы плагина composer
     */
    public function testPluginMethods(): void
    {
        $handler = new Plugin();
        $handler->activate($this->getComposerMock(), $this->getIoMock());
        $handler->deactivate($this->getComposerMock(), $this->getIoMock());
        $handler->uninstall($this->getComposerMock(), $this->getIoMock());
        $this->assertTrue(true);
    }
}
