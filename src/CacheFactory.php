<?php

namespace Bclib\GetBooksFromAlma;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheFactory
{
    public static function build(): AdapterInterface
    {
        $cache_namespace = 'books-from-alma';

        if (extension_loaded('redis')) {
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            if ($redis->ping()) {
                $redis->set('foo', 'bar');
                return new RedisAdapter($redis, $cache_namespace);
            }
        }

        $cache_dir = __DIR__ . '/../cache';
        return new FilesystemAdapter($cache_namespace, 0, $cache_dir);
    }
}