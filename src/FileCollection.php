<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use Fi1a\Collection\AbstractInstanceCollection;

/**
 * Коллекция файлов
 */
class FileCollection extends AbstractInstanceCollection implements FileCollectionInterface
{
    /**
     * @inheritDoc
     */
    protected function factory($key, $value)
    {
        return new File((string) $value);
    }

    /**
     * @inheritDoc
     */
    protected function isInstance($value): bool
    {
        return $value instanceof FileInterface;
    }

    /**
     * @inheritDoc
     */
    public function isPathExists(string $path): bool
    {
        /** @var FileInterface $file */
        foreach ($this as $file) {
            if ($file->getPath() === $path) {
                return true;
            }
        }

        return false;
    }
}
