<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: ResourceUsesController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\ResourceUses;
class ResourceUsesController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
      //  $params = $request->getQueryParams();
        //$term = array_key_exists('q', $params) ? $params['q'] : null;

        $results = ResourceUses::getResourceUses($request);

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a resourceUse
    public function view(Request $request, Response $response, array $args) {
        $resourceusesID = $args['resourceUsesID'];
        $results = ResourceUses::getResourceUsesById($resourceusesID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    //create a resourceUse
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResourceUse($request);

        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $resourceUse = ResourceUses::createResourceUses($request);
        $results = [
            'status' => "Ingredient created",
            'data' => $resourceUse
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update resourceUse
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateResourceUse($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $reourceUse = ResourceUses::updateResourceUses($request);
        $status = $reourceUse ? "ResourceUse has been updated." : "ResourceUse cannot be updated.";
        $status_code = $reourceUse ? 200 : 500;
        $results['status'] = $status;
        if($reourceUse){
            $results['data'] = $reourceUse;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $reourceUse = ResourceUses::deleteResourceUses($request);
        $status = $reourceUse ? "ResourceUse has been deleted." : "ResourceUse cannot be deleted.";
        $status_code = $reourceUse ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}