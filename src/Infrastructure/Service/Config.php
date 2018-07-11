<?php

namespace Dykyi\Infrastructure\Service;

/**
 * Class Config
 *
 * @package Dykyi\Infrastructure\Service
 */
class Config
{
    /**
     * Parse function
     *
     * @param array $envConfigs Have All config params
     *
     * @return array
     */
    public static function parse(array $envConfigs): array
    {
        $keys = [];
        foreach ($envConfigs as $item) {
            $elements = explode('=', $item);
            $keys[$elements[0]] = $elements[1];
        }

        return $keys;
    }
}