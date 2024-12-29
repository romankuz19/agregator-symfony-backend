<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Cache;

use Predis\Client;

class RedisService
{
    private Client $redis;

    public function __construct(string $redisUrl)
    {
        $this->redis = new Client($redisUrl);
    }

    public function set(string $key, mixed $value, int $ttl): void
    {
        $this->redis->set(key: $key, value: $value, expireResolution: 'EX', expireTTL: $ttl);
    }

    public function get(string $key): ?string
    {
        return $this->redis->get($key);
    }

    public function has(string $key): bool
    {
        return (bool) $this->redis->exists($key);
    }

    public function delete(string $key): void
    {
        $this->redis->del([$key]);
    }
}
