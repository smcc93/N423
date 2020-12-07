<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: resourceController.php
 * Description:
 */

namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\Resource;

class resourceController
{
    //list all resources
    public function index(Request $request, Response $response, array $args) {


        //get querystring variables from ulr
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if(!is_null($term)){
            $results = Resource::searchResources($term);
        }else{
            $results = Resource::getResources($request);
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a resource
    public function view(Request $request, Response $response, array $args) {
        $resourceID = $args['resourceID'];
        $results = Resource::getResourceByID($resourceID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //create a resource
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResource($request);

        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $resource = Resource::createResource($request);
        $results = [
            'status' => "Resource created",
            'data' => $resource
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update resource
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResource($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $resource = Resource::updateResource($request);
        $status = $resource ? "Resource has been updated." : "Resource cannot be updated.";
        $status_code = $resource ? 200 : 500;
        $results['status'] = $status;
        if($resource){
            $results['data'] = $resource;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $resource = Resource::deleteResource($request);
        $status = $resource ? "Resource has been deleted." : "Resource cannot be deleted.";
        $status_code = $resource ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

}