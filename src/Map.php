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
    public static function fromArray(array $map): MapInterface
    {
        $instance = new Map();
        foreach ($map as $item) {
            $instance->add($item['group'] ?: '', $item['path'] ?: '');
        }

        return $instance;
    }
}
