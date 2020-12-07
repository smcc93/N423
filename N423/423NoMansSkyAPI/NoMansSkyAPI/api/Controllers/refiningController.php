<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: refiningController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\Refining;

class refiningController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

            $results = Refining::getRefining($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a refining
    public function view(Request $request, Response $response, array $args) {
        $refiningID = $args['refiningID'];
        $results = Refining::getRefiningById($refiningID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    //create a refining
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateRefining($request);

        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $refining = Refining::createRefining($request);
        $results = [
            'status' => "Refining created",
            'data' => $refining
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update refining
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateRefining($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $refining = Refining::updateRefining($request);
        $status = $refining ? "Refining has been updated." : "Refining cannot be updated.";
        $status_code = $refining ? 200 : 500;
        $results['status'] = $status;
        if($refining){
            $results['data'] = $refining;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $refining = Refining::deleteRefining($request);
        $status = $refining ? "Refining has been deleted." : "Refining cannot be deleted.";
        $status_code = $refining ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}