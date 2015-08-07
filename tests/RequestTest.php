<?php

use Mcprohosting\CloudFlare\Request\HttpRequest;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $response = Mockery::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('json')->andReturn(json_decode('{
            "result": {"a": "b"},
            "success": false,
            "errors": [{"code":1003,"message":"Invalid or missing zone id."}],
            "messages": []
        }', true));

        $request = Mockery::mock('GuzzleHttp\Message\RequestInterface');
        $client = Mockery::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('createRequest')->with('post', 'SomeUrl/aRoute', [
            'headers' => [
                'X-Auth-Key' => 'FooKey',
                'X-Auth-Email' => 'FooEmail'
            ], 'json' => 'bar'
        ])->andReturn($request);
        $client->shouldReceive('send')->with($request)->andReturn($response);

        $resolver = Mockery::mock('Mcprohosting\CloudFlare\Resolver');
        $resolver->shouldReceive('resolve')->with('someMethod', [])->andReturn([
            'method'     => 'post',
            'route'      => '/aRoute',
            'parameters' => 'bar'
        ]);

        $request = new HttpRequest($client, $resolver);
        $request->setEmail('FooEmail');
        $request->setKey('FooKey');
        $request->setBaseUrl('SomeUrl');
        $r = $request->someMethod();

        $this->assertInstanceOf('Mcprohosting\CloudFlare\Response\GuzzleResponse', $r);
        $this->assertFalse($r->didSucceed());
        $this->assertEquals([['code' => 1003, 'message' => 'Invalid or missing zone id.']], $r->getErrors());
        $this->assertEquals(['a' => 'b'], $r->getData());
        $this->assertEquals('b', $r->a);
    }
}
