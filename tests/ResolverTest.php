<?php

use \Mcprohosting\CloudFlare\Resolver;

class ResolverTest extends PHPUnit_Framework_TestCase
{
    public function testResolvesRouteArrayArgument()
    {
        $resolver = new Resolver;
        $this->assertEquals([
            'method'     => 'get',
            'route'      => '/zones/a/dns_records/b',
            'parameters' => []
        ], $resolver->resolve('dnsGet', [['zone_id' => 'a', 'dns_id' => 'b']]));
    }

    public function testResolvesRouteListArgument()
    {
        $resolver = new Resolver;
        $this->assertEquals([
            'method'     => 'get',
            'route'      => '/zones/a/dns_records/b',
            'parameters' => []
        ], $resolver->resolve('dnsGet', ['a', 'b']));
    }

    public function testAssignsExtraToParams()
    {
        $resolver = new Resolver;
        $this->assertEquals([
            'method'     => 'post',
            'route'      => '/zones/z/dns_records',
            'parameters' => ['type' => 't', 'name' => 'n', 'content' => 'c', 'ttl' => 'tt']
        ], $resolver->resolve('dnsCreate', ['z', 't', 'n', 'c', 'tt']));
    }

    public function testHandlesMagicData()
    {
        $resolver = new Resolver;
        $this->assertEquals([
            'method'     => 'put',
            'route'      => '/zones/z/dns_records/a',
            'parameters' => ['foo' => 'bar']
        ], $resolver->resolve('dnsUpdate', ['z', 'a', ['foo' => 'bar']]));
    }
} 