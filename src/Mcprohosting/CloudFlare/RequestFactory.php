<?php

namespace Mcprohosting\CloudFlare;

use GuzzleHttp\Client;
use Mcprohosting\CloudFlare\Request\HttpRequest;

class RequestFactory
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
    public $baseUrl = 'https://api.cloudflare.com/client/v4/';

    /**
     * Creates a new HttpRequest.
     *
     * @return HttpRequest
     */
    public function make()
    {
        $request = new HttpRequest(new Client, new Resolver);

        $request->setEmail($this->authEmail);
        $request->setKey($this->authKey);
        $request->setBaseUrl($this->baseUrl);

        return $request;
    }

    /**
     * Updates the factory's auth email.
     *
     * @param string $authEmail
     */
    public function setEmail($authEmail)
    {
        $this->authEmail = $authEmail;
    }

    /**
     * Updates the factory's auth key.
     *
     * @param string $authKey
     */
    public function setKey($authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * Updates the factory's baseUrl.
     *
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function __call($method, $arguments)
    {
        $request = $this->make();

        return call_user_func_array(array($request, $method), $arguments);
    }
}
