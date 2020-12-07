<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: ResourceSourcesController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\ResourceSources;
class ResourceSourcesController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
       // $params = $request->getQueryParams();
       // $term = array_key_exists('q', $params) ? $params['q'] : null;


            $results = ResourceSources::getResourceSources($request);

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a resourceSource
    public function view(Request $request, Response $response, array $args) {
        $resourceSourceID = $args['resourceSourceID'];
        $results = ResourceSources::getResourceSourcesById($resourceSourceID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    //create a resourceSource
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResourceSource($request);

        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $resourceSource = ResourceSources::createResourceSources($request);
        $results = [
            'status' => "ResourceSource created",
            'data' => $resourceSource
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update resourceSource
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResourceSource($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $resourceSource = ResourceSources::updateResourceSources($request);
        $status = $resourceSource ? "resourceSource has been updated." : "resourceSource cannot be updated.";
        $status_code = $resourceSource ? 200 : 500;
        $results['status'] = $status;
        if($resourceSource){
            $results['data'] = $resourceSource;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $resourceSource = ResourceSources::deleteResourceSources($request);
        $status = $resourceSource ? "resourceSource has been deleted." : "resourceSource cannot be deleted.";
        $status_code = $resourceSource ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}