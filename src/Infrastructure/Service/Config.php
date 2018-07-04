<?php

namespace Building\Infrastructure\Service;

/**
 * Class Config
 * @package Building\Infrastructure\Service
 */
class Config
{
    /**
     * @param array $envConfigs
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