<?php

namespace Dykyi\Test\Application;

use Dykyi\Infrastructure\Service\Config;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 *
 * @coversDefaultClass \Dykyi\Infrastructure\Service\Config
 *
 * @package Dykyi\Test\Application
 */
class ConfigTest extends TestCase
{

    public function configProvider(): array
    {
        return [
            [['debug=1']],
        ];
    }


    /**
     * @covers ::parse()
     *
     * @dataProvider configProvider
     *
     * @param array $configItem
     */
    public function testGetConfig(array $configItem)
    {
        $config = Config::parse($configItem);
        $this->assertArrayHasKey('debug', $config);
    }
}