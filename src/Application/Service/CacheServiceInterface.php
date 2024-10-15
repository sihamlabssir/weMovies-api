<?php

namespace App\Application\Service;

interface CacheServiceInterface
{
    public function get(string $key): ?string;
    public function set(string $key, $value);
}
