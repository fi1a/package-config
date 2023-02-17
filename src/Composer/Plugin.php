<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Fi1a\PackageConfig\Composer\Commands\CommandProvider;
use Fi1a\PackageConfig\StorageAdapters\StorageAdapterInterface;

/**
 * Плагин
 */
class Plugin implements PluginInterface, EventSubscriberInterface, Capable
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onInstall',
            ScriptEvents::POST_UPDATE_CMD => 'onUpdate',
        ];
    }

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $autoloadPath = (string) $composer->getConfig()->get('vendor-dir') . '/autoload.php';
        if (is_file($autoloadPath)) {
            /**
             * @psalm-suppress UnresolvableInclude
             * @psalm-suppress PossiblyNullReference
             */
            require_once $autoloadPath;
            require_once __DIR__ . '/../configure.php';
        }
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities(): array
    {
        return [ComposerCommandProvider::class => CommandProvider::class];
    }

    /**
     * Обработчик события установки
     */
    public function onInstall(Event $event): void
    {
        $this->process($event->getComposer());
    }

    /**
     * Обработчик события обновления
     */
    public function onUpdate(Event $event): void
    {
        $this->process($event->getComposer());
    }

    /**
     * Процесс создания карты файлов конфигурации
     */
    protected function process(Composer $composer): void
    {
        /** @var StorageAdapterInterface $adapter */
        $adapter = di()->get(StorageAdapterInterface::class);
        $process = new Process($composer);
        $adapter->write($process->process()->toArray());
    }
}
