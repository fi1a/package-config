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
    public function add(string $group, FileInterface $file): bool
    {
        if (!$group) {
            throw new InvalidArgumentException('Группа не может быть пустой');
        }
        if ($this->groups->isPathExists($file->getPath())) {
            throw new ErrorException(sprintf('Файл конфигурации %s уже добавлен', $file->getPath()));
        }

        if (!$this->groups->has($group)) {
            $this->groups->set($group, []);
        }

        /** @var FileCollectionInterface $groupCollection */
        $groupCollection = $this->groups->get($group);
        $groupCollection[] = $file;

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
        /** @var FileInterface[] $groupArray */
        $groupArray = $collection->getArrayCopy();
        usort($groupArray, function (FileInterface $a, FileInterface $b): int {
            return $a->getSort() - $b->getSort();
        });

        return new FileCollection($groupArray);
    }

    /**
     * @inheritDoc
     */
    public function addArray(array $map): bool
    {
        foreach ($map as $item) {
            $this->add($item['group'] ?? '', new File($item['path'] ?? '', $item['sort'] ?? 500));
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
                    'sort' => $file->getSort(),
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
