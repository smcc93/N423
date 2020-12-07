<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: componentController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\Component;


class componentController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if(!is_null($term)){
            $results = Component::searchComponents($term);
        }else{
            $results = Component::getComponents($request);
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a component
    public function view(Request $request, Response $response, array $args) {
        $componentID = $args['componentID'];
        $results = Component::getComponentByID($componentID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    //create a component
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateComponent($request);

        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $component = Component::createComponent($request);
        $results = [
            'status' => "Component created",
            'data' => $component
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update ingredient
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateComponent($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $component = Component::updateComponent($request);
        $status = $component ? "Component has been updated." : "Component cannot be updated.";
        $status_code = $component ? 200 : 500;
        $results['status'] = $status;
        if($component){
            $results['data'] = $component;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $component = Component::deleteComponent($request);
        $status = $component ? "Component has been deleted." : "Component cannot be deleted.";
        $status_code = $component ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}