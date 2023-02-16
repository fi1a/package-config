<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use Fi1a\Collection\AbstractInstanceCollection;

/**
 * Коллекция групп
 */
class GroupCollection extends AbstractInstanceCollection implements GroupCollectionInterface
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        return new FileCollection((array) $value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof FileCollectionInterface;
    }

    /**
     * @inheritDoc
     */
    public function isPathExists(string $path): bool
    {
        /** @var FileCollectionInterface $collection */
        foreach ($this as $collection) {
            if ($collection->isPathExists($path)) {
                return true;
            }
        }

        return false;
    }
}
