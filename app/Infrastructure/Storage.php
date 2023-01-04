<?php

namespace App\Infrastructure;

class Storage
{
    private static self $singleton;

    public array $data = [
        'User' => [],
        'Book' => [],
        'BookCirculation' => [],
    ];

    private function __construct()
    {
    }

    public static function getInstance(): static
    {
        if (!isset(static::$singleton)) {
            static::$singleton = new static();
        }

        return static::$singleton;
    }
}