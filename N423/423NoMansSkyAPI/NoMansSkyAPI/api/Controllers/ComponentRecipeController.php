<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: ComponentRecipeController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\ComponentRecipe;
class ComponentRecipeController
{
    public function index(Request $request, Response $response, array $args) {
        //get querystring variables from ulr
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if(!is_null($term)){
            $results = ComponentRecipe::searchComponentRecipes($term);
        }else{
            $results = ComponentRecipe::getComponentRecipes($request);
        }
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //view a componentRecipe
    public function view(Request $request, Response $response, array $args) {
        $componentID = $args['recipeID'];
        $results = ComponentRecipe::getComponentRecipeById($componentID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    //create a componentRecipe
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

        $componentRecipe = ComponentRecipe::createComponentRecipe($request);
        $results = [
            'status' => "componentRecipe created",
            'data' => $componentRecipe
        ];
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    //update componentRecipe
    public function update(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateComponent($request);

        //if validation failed
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }

        $componentRecipe = ComponentRecipe::updateComponentRecipe($request);
        $status = $componentRecipe ? "componentRecipe has been updated." : "componentRecipe cannot be updated.";
        $status_code = $componentRecipe ? 200 : 500;
        $results['status'] = $status;
        if($componentRecipe){
            $results['data'] = $componentRecipe;
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    public function delete(Request $request, Response $response, array $args){
        $componentRecipe = ComponentRecipe::deleteComponentRecipe($request);
        $status = $componentRecipe ? "componentRecipe has been deleted." : "componentRecipe cannot be deleted.";
        $status_code = $componentRecipe ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }
}