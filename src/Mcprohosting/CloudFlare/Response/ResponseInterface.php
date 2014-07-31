<?php

namespace Mcprohosting\CloudFlare\Response;

interface ResponseInterface
{
    /**
     * Get an array of data.
     *
     * @return array
     */
    public function getData();

    /**
     * An array of errors, or null if no errors occurred.
     *
     * @return array|null
     */
    public function getErrors();

    /**
     * Whether the request was successful.
     *
     * @return bool
     */
    public function didSucceed();
} 