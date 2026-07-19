<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trusted proxies — production mein actual load balancer IP daalo
     */
    protected $proxies = '*';

    /**
     * Headers to trust from proxy
     */
    protected $headers = [
        Request::HEADER_FORWARDED,
        Request::HEADER_X_FORWARDED_FOR,
        Request::HEADER_X_FORWARDED_HOST,
        Request::HEADER_X_FORWARDED_PORT,
        Request::HEADER_X_FORWARDED_AWS,
        Request::HEADER_X_FORWARDED_PROTO,
    ];
}