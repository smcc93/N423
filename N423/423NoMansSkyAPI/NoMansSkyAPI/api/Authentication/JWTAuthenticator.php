<?php
/**
 * Author: Stewart McCalley
 * Date: 12/6/2020
 * File: JWTAuthenticator.php
 * Description:
 */

namespace NoMansSkyAPI\Authentication;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\User;

class JWTAuthenticator
{
    public function __invoke(Request $request, Response $response, $next) {
        //if the header named Authorization does not exist, returns an error
        if(!$request->hasHeader('Authorization')){
            $results = ['Status' => 'Authorization header not available'];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        //retrieve the header and the token
        $auth = $request->getHeader('Authorization');

        $token = substr($auth[0], strpos($auth[0], ' ') +1);

        //validate the token
        if(!User::validateJWT($token)){
            return $response->withJson(['Status' => 'Authentication failed'], 401, JSON_PRETTY_PRINT);

        }
        //user has been authenticated
        $response = $next($request, $response);
        return $response;
    }
}