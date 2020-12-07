<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: CategoryController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Models\Category;

class CategoryController
{
    //list all categories
    public function index(Request $request, Response $response, array $args){
        $results = Category::getCategories($request);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    
    //view a category
    public function view(Request $request, Response $response, array $args){
        $categoryID = $args['categoryID'];
        $results = Category::getCategoryByID($categoryID);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
    
    //create a category
    public function create(Request $request, Response $response, array $args){
        //validate the request
        $validation = Validator::validateCategory($request);
        if(!$validation){
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }
        
        $category = Categery::createCategory($request);
        $results = [
            'status' => "Category created",
            'data' => $category
        ];
        return $response->withJson($results, 500, JSON_PRETTY_PRINT);
    }
    
    //update a category
    public function update(Request $request, Response $response, array $args){
        $validation = Validator::validateCategory($request);
        if(!$validation){
            $results['status'] = "Validation failed.";
            $results['errors'] = Validator::getErrors();
            return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
        }
        
        $category = Category::updateCategory($request);
        $status = $category ? "Category has been updated" : "Category cannot be updated";
        $status_code = $category ? 200 : 500;
        $results['status'] = $status;
        if($category){
            $results['data'] = $category;
        }
        return $response -> withJson($results, 500,JSON_PRETTY_PRINT);
    }
    
    //delete a category
    public function delete(Request $request, Response $response, array $args){
        $category = Category::deleteCategory($request);
        $status = $category ? "Category has been deleted." : "Category cannot be deleted.";
        $status_code = $category ? 200 : 500;
        $results = ['status' => $status];
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

}