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

    /*public function handle( $request, Closure $next )
    {
        if ( str_contains( $request->getRequestUri(), '/api/' ) ) {
            return $next( $request );
        }
        return parent::handle( $request, $next );
    }*/
    protected $except = [

    ];
}
