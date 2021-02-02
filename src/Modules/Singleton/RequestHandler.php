<?php

namespace Arcphysx\Laradrive\Modules\Singleton;

use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Arcphysx\Laradrive\Laradrive;
use Arcphysx\Laradrive\Modules\Contract\HttpClientModuleContract;
use GuzzleHttp\Psr7\Request;

class RequestHandler implements HttpClientModuleContract
{
    private static $INSTANCE = null;

    private $handlerStack = null;

    private function __construct(){
        $this->handlerStack = new HandlerStack();
        $this->handlerStack->setHandler(new CurlHandler());
    }

    public static function _get()
    {
        if(self::$INSTANCE == null){
            self::$INSTANCE = new RequestHandler();
        }
        return self::$INSTANCE;
    }

    public function handler()
    {
        $this->setGlobalRequestConfig();
        return $this->handlerStack;
    }
    
    private function setGlobalRequestConfig()
    {
        $this->appendAcceptJsonHeader();
        $this->appendAuthorizationHeader();
        $this->appendKeyOnQueryParam();
    }

    private function appendAcceptJsonHeader()
    {
        $this->handlerStack->push(Middleware::mapRequest(function (RequestInterface $request) {
            return $request->withHeader('Accept', 'application/json');
        }));
    }

    private function appendAuthorizationHeader()
    {
        $this->handlerStack->push(Middleware::mapRequest(function (RequestInterface $request) {
            return $request->withHeader('Authorization', 'Bearer '.Laradrive::accessToken());
        }));
    }

    public function appendKeyOnQueryParam()
    {
        $this->handlerStack->push(Middleware::mapRequest(function (RequestInterface $request) {
            return new Request(
                $request->getMethod(),
                $this->parseUri($request),
                $request->getHeaders(),
                $request->getBody(),
                $request->getProtocolVersion()
            );
        }));
    }

    public function parseUri(RequestInterface $request)
    {
        $uri  = $request->getUri();
        $uri .= ( $uri->getQuery() ? '&' : '?' );
        $uri .= http_build_query([
            'key' => Laradrive::apiKey(),
        ]);
        return $uri;
    }
}