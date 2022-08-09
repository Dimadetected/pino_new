<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8/webhook',
        '/5500151155:AAHq8UDjMWgv5-lQLBbuTHFzwJqYYZmFea4/webhook',
    ];
}
