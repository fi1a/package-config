<?php

declare(strict_types=1);

namespace Fi1a\PackageConfig;

use InvalidArgumentException;

/**
 * Файл
 */
class File implements FileInterface
{
    /**
     * @var string
     */
    protected $path;

    public function __construct(string $path)
    {
        if (!$path) {
            throw new InvalidArgumentException('Не передан путь к конфигурационному файлу');
        }

        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
