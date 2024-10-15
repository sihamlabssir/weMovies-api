<?php

namespace App\Infrastructure\Service;

use App\Application\Service\CacheServiceInterface;
use Predis\Client;

class RedisService implements CacheServiceInterface
{
    public function __construct(private readonly Client $redisClient, private readonly int $ttl)
    {
    }

    public function get(string $key): ?string
    {
        return $this->redisClient->get($key);
    }

    public function set(string $key, $value)
    {
        $this->redisClient->set($key, $value);
        $this->redisClient->expire($key, $this->ttl);
    }
}
