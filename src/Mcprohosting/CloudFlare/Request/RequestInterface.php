<?php

namespace Mcprohosting\CloudFlare\Request;

use Mcprohosting\CloudFlare\Response\ResponseInterface;

/**
 * @method ResponseInterface zoneCreate(mixed $name, bool $jump_start = null, array $organization = null)
 * @method ResponseInterface zoneList(mixed $name = null, string $status = null, integer $page = null, integer $per_page = null, string $order = null, string $direction = null, string $match = null)
 * @method ResponseInterface zoneGet(mixed $id)
 * @method ResponseInterface zonePause(mixed $id)
 * @method ResponseInterface zoneResume(mixed $id)
 * @method ResponseInterface zonePurge(mixed $id, bool $purge_everything)
 * @method ResponseInterface zonePurgeFiles(mixed $id, array $files = null)
 * @method ResponseInterface zoneDelete(mixed $id)
 * @method ResponseInterface plansList(mixed $zone_id)
 * @method ResponseInterface plansGet(mixed $zone_id, string $plan_id)
 * @method ResponseInterface plansSet(mixed $zone_id, string $plan_id)
 * @method ResponseInterface dnsCreate(mixed $zone_id, string $type = null, string $name = null, string $content = null, integer $ttl = null)
 * @method ResponseInterface dnsList(mixed $zone_id, string $name = null, string $content= null, string $vanity_name_server_record = null, integer $page = null, integer $per_page = null, string $order = null, string $direction = null, string $match = null)
 * @method ResponseInterface dnsGet(mixed $zone_id, string $dns_id)
 * @method ResponseInterface dnsUpdate(mixed $zone_id, string $dns_id, array $__data__)
 * @method ResponseInterface dnsDelete(mixed $zone_id, string $dns_id)
 */
interface RequestInterface
{
    /**
     * Updates the key used to auth to the CloudFlare API - for this request only!
     *
     * @param string $key
     * @return RequestInterface
     */
    public function setKey($key);

    /**
     * Updates the email used to auth to the CloudFlare API - for this request only!
     *
     * @param string $email
     * @return RequestInterface
     */
    public function setEmail($email);

    /**
     * Updates the base URL of the CloudFlare API - for this request only!
     *
     * @param string $url
     * @return RequestInterface
     */
    public function setBaseUrl($url);

    /**
     * Runs a GET request against the given route, with option parameters passed.
     * @param string $route
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function get($route, $parameters = []);

    /**
     * Runs a POST request against the given route, with option parameters passed.
     * @param string $route
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function post($route, $parameters = []);

    /**
     * Runs a PUT request against the given route, with option parameters passed.
     * @param string $route
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function put($route, $parameters = []);

    /**
     * Runs a PATCH request against the given route, with option parameters passed.
     * @param string $route
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function patch($route, $parameters = []);

    /**
     * Runs a DELETE request against the given route, with option parameters passed.
     * @param string $route
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function delete($route, $parameters = []);
} 