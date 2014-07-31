<?php

namespace Mcprohosting\CloudFlare;

class Resolver
{
    public $routes = [
        'zoneCreate' => [
            'method'     => 'post',
            'route'      => '/zones',
            'parameters' => ['name', 'jump_start', 'organization']
        ],
        'zoneList'   => [
            'method'     => 'get',
            'route'      => '/zones',
            'parameters' => ['name', 'status', 'page', 'per_page', 'order', 'direction', 'match']
        ],
        'zoneGet'    => [
            'method'     => 'get',
            'route'      => '/zones/:id',
            'parameters' => ['id']
        ],
        'zonePause'  => [
            'method'     => 'put',
            'route'      => '/zones/:id/pause',
            'parameters' => ['id']
        ],
        'zoneResume' => [
            'method'     => 'put',
            'route'      => '/zones/:id/unpause',
            'parameters' => ['id']
        ],
        'zonePurge'  => [
            'method'     => 'delete',
            'route'      => '/zones/:id/purge_cache',
            'parameters' => ['id', 'purge_everything']
        ],
        'zonePurgeFiles'  => [
            'method'     => 'delete',
            'route'      => '/zones/:id/purge_cache',
            'parameters' => ['id', 'files']
        ],
        'zoneDelete' => [
            'method'     => 'get',
            'route'      => '/zones/:id',
            'parameters' => ['id']
        ],
        'plansList'  => [
            'method'     => 'get',
            'route'      => '/zones/:zone_id/plans',
            'parameters' => ['zone_id']
        ],
        'plansGet'  => [
            'method'     => 'get',
            'route'      => '/zones/:zone_id/plans/:plan_id',
            'parameters' => ['zone_id', 'plan_id']
        ],
        'plansSet'   => [
            'method'     => 'put',
            'route'      => '/zones/:zone_id/plans/:plan_id/subscribe',
            'parameters' => ['zone_id', 'plan_id']
        ],
        'dnsCreate'  => [
            'method'     => 'post',
            'route'      => '/zones/:zone_id/dns_records',
            'parameters' => ['zone_id', 'type', 'name', 'content', 'ttl']
        ],
        'dnsList'    => [
            'method'     => 'get',
            'route'      => '/zones/:zone_id/dns_records',
            'parameters' => [
                'zone_id',
                'name',
                'content',
                'vanity_name_server_record',
                'page',
                'per_page',
                'order',
                'direction',
                'match'
            ]
        ],
        'dnsGet'     => [
            'method'     => 'get',
            'route'      => '/zones/:zone_id/dns_records/:dns_id',
            'parameters' => ['zone_id', 'dns_id']
        ],
        'dnsUpdate'  => [
            'method'     => 'put',
            'route'      => '/zones/:zone_id/dns_records/:dns_id',
            'parameters' => ['zone_id', 'dns_id', '__data__']
        ],
        'dnsDelete'  => [
            'method'     => 'delete',
            'route'      => '/zones/:zone_id/dns_records/:dns_id',
            'parameters' => ['zone_id', 'dns_id']
        ]
    ];

    /**
     * Parses the given method and arguments for submission to the CloudFlare API.
     *
     * @param string $method
     * @param array  $arguments
     * @return array
     * @throws \BadMethodCallException
     */
    public function resolve($method, $arguments)
    {
        if (!array_key_exists($method, $this->routes)) {
            throw new \BadMethodCallException('Method [' . $method . '] does not exist.');
        }

        $output = $this->routes[$method];

        $output['parameters'] = $this->formatArguments($arguments, $output['parameters']);
        $output = $this->applyToRoute($method, $output);


        if (array_key_exists('__data__', $output['parameters'])) {
            $output['parameters'] = $output['parameters']['__data__'];
        }
        
        return $output;
    }

    /**
     * Normalizes the arguments into a single, nice associative array.
     *
     * @param array $arguments
     * @param array $parameters
     * @return array
     */
    protected function formatArguments($arguments, $parameters)
    {
        if (count($arguments) === 1 && is_array($arguments[0])) {
            return $arguments[0];
        }

        $output = [];
        foreach ($arguments as $index => $argument) {
            $output[$parameters[$index]] = $argument;
        }

        return $output;
    }

    /**
     * Swaps route arguments out with their appropriate arguments, then removes the arguments consumed.
     *
     * @param string $method
     * @param array $route
     * @return array
     * @throws \BadMethodCallException
     */
    protected function applyToRoute($method, $route)
    {
        $matches = [];
        preg_match_all('/:[a-z_]+/', $route['route'], $matches);

        if (!count($matches)) {
            return $route;
        }

        foreach ($matches[0] as $match) {
            $key = ltrim($match, ':');

            if (!array_key_exists($key, $route['parameters'])) {
                throw new \BadMethodCallException('Method [' . $method . '] needed a [' . $match . '] but did not receive one.');
            }

            $route = str_replace($match, $route['parameters'][$key], $route);
            unset($route['parameters'][$key]);
        }

        return $route;
    }
} 