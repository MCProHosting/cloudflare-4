<?php

namespace Mcprohosting\CloudFlare\Request;

use GuzzleHttp\ClientInterface;
use Mcprohosting\CloudFlare\Resolver;
use Mcprohosting\CloudFlare\Response\GuzzleResponse;
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
class HttpRequest implements RequestInterface
{
    /**
     * Authentication key for CloudFlare.
     *
     * @var string
     */
    protected $authKey;

    /**
     * Authentication email for CloudFlare.
     *
     * @var string
     */
    protected $authEmail;

    /**
     * URL upon which CloudFlare requests are based.
     *
     * @var string
     */
    public $baseUrl;

    /**
     * Guzzle client instance.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Route resolver instance.
     *
     * @var Resolver
     */
    private $resolver;

    public function __construct(ClientInterface $client, Resolver $resolver)
    {
        $this->client = $client;
        $this->resolver = $resolver;
    }

    public function setKey($key)
    {
        $this->authKey = $key;
    }

    public function setEmail($email)
    {
        $this->authEmail = $email;
    }

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    public function get($route, $parameters = [])
    {
        return $this->request('get', $route, ['query' => $parameters]);
    }

    public function post($route, $parameters = [])
    {
        return $this->request('post', $route, ['json' => $parameters]);
    }

    public function delete($route, $parameters = [])
    {
        return $this->request('delete', $route, ['json' => $parameters]);
    }

    public function put($route, $parameters = [])
    {
        return $this->request('put', $route, ['json' => $parameters]);
    }

    public function patch($route, $parameters = [])
    {
        return $this->request('patch', $route, ['json' => $parameters]);
    }

    /**
     * Runs a request against the CF API, adding appropriate auth details to the options.
     *
     * @param string $method
     * @param string $route
     * @param array  $options
     * @return GuzzleResponse
     */
    protected function request($method, $route, $options)
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($route, '/');

        $request = $this->client->createRequest($method, $url, $options + [
            'headers' => [
                'X-Auth-Key' => $this->authKey,
                'X-Auth-Email' => $this->authEmail
            ]
        ]);

        return new GuzzleResponse($this->client->send($request));
    }

    public function __call($method, $arguments)
    {
        $resolved = $this->resolver->resolve($method, $arguments);
        $method = $resolved['method'];

        return $this->$method($resolved['route'], $resolved['parameters']);
    }
}
