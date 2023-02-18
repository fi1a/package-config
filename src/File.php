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

    /**
     * @var int
     */
    protected $sort;

    public function __construct(string $path, ?int $sort)
    {
        if (!$path) {
            throw new InvalidArgumentException('Не передан путь к конфигурационному файлу');
        }
        if ($sort === null) {
            $sort = 500;
        }
        $this->path = $path;
        $this->sort = $sort;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getSort(): int
    {
        return $this->sort;
    }
}
