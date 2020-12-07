<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: SourcesController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\Sources;
class SourcesController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;


            $results = Sources::getSources($request);

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a source
    public function view(Request $request, Response $response, array $args) {
        $sourceID = $args['sourceID'];
        $results = Sources::getSourceByID($sourceID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }





}