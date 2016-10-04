# PHP-Utils

## Cache

### KVCache

Use SAE KVDB to do simple Cache

```php
// Init
$cache = new KVCache();
$cache->cache_prefix = 'list';
$cache->cache_time = 60;
// Set
$cache->set($key, json_encode($data));
// Get
json_decode($cache->get($key));
// Curl and cache
$cache->get_data($key, 'http://qq.com');
```
