<?php

namespace AWT\Http\Middleware;

use AWT\Contracts\ApiLoggerInterface;
use Closure;

class ApiLogger
{
    protected $logger;

    public function __construct(ApiLoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        return $response;
    }

    public function terminate($request, $response){
        $this->logger->saveLogs($request,$response);
    }
}
