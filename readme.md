# cloudflare-4

A PHP package for the next generation of CloudFlare API.

### Usage Overview

```php
// Set up the request Factory
$factory = new RequestFactory();
$factory->setEmail('FooEmail');
$factory->setKey('FooKey');

// Make a request
$request = $factory->make();
// And run a response
$response = $request->zoneList();

// Alternately, `$response = $factory->zoneList();` does the same thing.

if (!$response->didSucceed()) {
    var_dump($response->getErrors());
    die('Something went wrong!');
} else {
    var_dump($response->getData());
}
```

### Available Methods

You can pass arguments in, or simply pass an associative array as the first argument, where keys are the argument names and values are the corresponding values. Example:

```
$factory->dnsGet('a', 'b');
// is the same as...
$factory->dnsGet([['zone_id' => 'a', 'dns_id' => 'b']]);
```

This may be preferable for functions with very long argument lists.

 * ResponseInterface zoneCreate(mixed $name, bool $jump_start = null, array $organization = null)
 * ResponseInterface zoneList(mixed $name = null, string $status = null, integer $page = null, integer $per_page = null, string $order = null, string $direction = null, string $match = null)
 * ResponseInterface zoneGet(mixed $id)
 * ResponseInterface zonePause(mixed $id)
 * ResponseInterface zoneResume(mixed $id)
 * ResponseInterface zonePurge(mixed $id, bool $purge_everything)
 * ResponseInterface zonePurgeFiles(mixed $id, array $files = null)
 * ResponseInterface zoneDelete(mixed $id)
 * ResponseInterface plansList(mixed $zone_id)
 * ResponseInterface plansGet(mixed $zone_id, string $plan_id)
 * ResponseInterface plansSet(mixed $zone_id, string $plan_id)
 * ResponseInterface dnsCreate(mixed $zone_id, string $type = null, string $name = null, string $content = null, integer $ttl = null)
 * ResponseInterface dnsList(mixed $zone_id, string $name = null, string $content= null, string $vanity_name_server_record = null, integer $page = null, integer $per_page = null, string $order = null, string $direction = null, string $match = null)
 * ResponseInterface dnsGet(mixed $zone_id, string $dns_id)
 * ResponseInterface dnsUpdate(mixed $zone_id, string $dns_id, array $__data__)
 * ResponseInterface dnsDelete(mixed $zone_id, string $dns_id)
 
For further information about specific functions, see the [CloudFlare documentation](http://developers.cloudflare.com/next/). All of these return instances of ResponseInterface, which implements the following methods:

 * `array getData()` - Returns the array of the "results" from the CloudFlare response.
 * `mixed getErrors()` - Returns the array of "errors" from the CloudFlare response.
 * `bool didSucceed()` - Returns the "success" of the CloudFlare response, true or false.
