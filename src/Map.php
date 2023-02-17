<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use ErrorException;
use InvalidArgumentException;

/**
 * Конфигурационные файлы
 */
class Map implements MapInterface
{
    /**
     * @var GroupCollectionInterface
     */
    protected $groups;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->groups = new GroupCollection();
    }

    /**
     * @inheritDoc
     */
    public function add(string $group, $path): bool
    {
        if (!$group) {
            throw new InvalidArgumentException('Группа не может быть пустой');
        }

        $filePath = $path instanceof FileInterface ? $path->getPath() : $path;
        if ($this->groups->isPathExists($filePath)) {
            throw new ErrorException(sprintf('Файл конфигурации %s уже добавлен', $filePath));
        }

        if (!$this->groups->has($group)) {
            $this->groups->set($group, []);
        }

        /** @var FileCollectionInterface $groupCollection */
        $groupCollection = $this->groups->get($group);
        $groupCollection[] = $path;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(string $group): FileCollectionInterface
    {
        if (!$this->groups->has($group)) {
            $this->groups->set($group, []);
        }
        /** @var FileCollectionInterface $collection */
        $collection = $this->groups->get($group);

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function addArray(array $map): bool
    {
        foreach ($map as $item) {
            $this->add($item['group'] ?? '', $item['path'] ?? '');
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $map = [];
        /**
         * @var string $group
         * @var FileCollectionInterface $files
         */
        foreach ($this->groups as $group => $files) {
            /** @var FileInterface $file */
            foreach ($files as $file) {
                $map[] = [
                    'group' => $group,
                    'path' => $file->getPath(),
                ];
            }
        }

        return $map;
    }

    /**
     * @inheritDoc
     */
    public static function createFromArray(array $map): MapInterface
    {
        $instance = new Map();
        $instance->addArray($map);

        return $instance;
    }
}
