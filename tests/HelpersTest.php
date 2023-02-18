<?php

declare(strict_types=1);

namespace Fi1a\Unit\PackageConfig;

use Fi1a\Config\ConfigValuesInterface;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование хелперов
 */
class HelpersTest extends TestCase
{
    /**
     * Возвращает значения группы конфигураций пакетов
     */
    public function testConfig(): void
    {
        $this->assertInstanceOf(ConfigValuesInterface::class, config('parameters'));
    }
}
