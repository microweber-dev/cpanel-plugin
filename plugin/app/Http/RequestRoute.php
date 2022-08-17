<?php
namespace App\Http;

use Illuminate\Http\Request;

class RequestRoute extends Request
{

    /**
     * Creates a POST JSON Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param string $uri The URI
     * @param string $method The HTTP method
     * @param array $parameters The query (GET) or request (POST) parameters
     * @param array $cookies The request cookies ($_COOKIE)
     * @param array $files The request files ($_FILES)
     * @param array $server The server parameters ($_SERVER)
     * @param string|resource|null $content The raw body data
     *
     * @return static
     */
    public static function fireRouteRequest($route, $request)
    {
        $params = $request->all();
        $requestFactory = function (array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) use ($params) {
            if (!isset($params["x-no-throttle"])) {
                $server['x-no-throttle'] = true;
            } else {
                $server['x-no-throttle'] = $params["x-no-throttle"];
                unset($params["x-no-throttle"]);
            }
            return new RequestRoute($query, $request, $attributes, $cookies, $files, $server, $content);
        };

        self::setFactory($requestFactory);

        $createRequest = self::create($route, $request->getMethod(), $params, $_COOKIE, $_FILES, $_SERVER);
      //   $createRequest->headers->set('accept', 'application/json');
        
        try {
            return app()->handle($createRequest);
        } catch(\Exception $e) {
            dd($route, $request->getMethod(), $e->getMessage());
        }

    }

}
