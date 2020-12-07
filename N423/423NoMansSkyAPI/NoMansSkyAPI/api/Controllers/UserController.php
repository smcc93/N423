<?php
/**
 * Author: Stewart McCalley
 * Date: 12/3/2020
 * File: UserController.php
 * Description:
 */
namespace NoMansSkyAPI\Controllers;

use NoMansSkyAPI\Models\Token;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use NoMansSkyAPI\Validation\Validator;
use NoMansSkyAPI\Models\User;
class UserController
{
// List users. The url may contain querystring parameters for login, authenticate with JWT or Bearer token.
    public function index(Request $request, Response $response, array $args)
    {
        $results = User::getUsers();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }

    // View a specific user by its id
    public function view(Request $request, Response $response, array $args)
    {
        $id = $request->getAttribute('id');
        $results = User::getUserById($id);
        $status_code = 200;
        if(!$results) {
            $status_code = 404;
            $results = ['Status' => 'User not found.'];
        }
        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }


    // Create a user when the user signs up an account
    public function create(Request $request, Response $response, array $args)
    {
        // Validate the request
        $validation = Validator::validateUser($request);

        // If validation failed
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        // Validation has passed; Proceed to create the professor
        $user = User::createUser($request);
        $results = [
            'status' => 'User created',
            'data' => $user
        ];
        return $response->withJson($results, 201, JSON_PRETTY_PRINT);
    }

    // Update a user
    public function update(Request $request, Response $response, array $args)
    {
        // Validate the request
        $validation = Validator::validateUser($request);

        // If validation failed
        if (!$validation) {
            $results['status'] = "Validation failed";
            $results['errors'] = Validator::getErrors();
            return $response->withJson($results, 500, JSON_PRETTY_PRINT);
        }

        $user = User::updateUser($request);
        $status = $user ? "User has been updated." : "User cannot be updated.";
        $status_code = $user ? 200 : 404;
        $results['status'] = $status;
        if($user) {
            $results['data'] = $user;
        }

        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    // Delete a user
    public function delete(Request $request, Response $response, array $args)
    {
        $user = User::deleteUser($request);
        $status = $user ? "User has been deleted." : "User cannot be deleted.";
        $status_code = $user ? 200 : 404;
        $results['status'] = $status;

        return $response->withJson($results, $status_code, JSON_PRETTY_PRINT);
    }

    //validate a user with a username and password, it returns a Bearer token on success or error on failure
    public function authBearer(Request $request, Response $response){
        //retrieve username and password from the request body
        $params= $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];

        //verify username and password
        $user = User::authenticateUser($username, $password);
        if(!$user){
            return $response->withJson(['Status' => 'Login Failed'], 401, JSON_PRETTY_PRINT);
        }

        //username and password are valid
        $token = Token::generateBearer($user->id);
        $results = ['Status' => 'Login successful', 'Token' => $token];

        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }


    //validate a user with username and password. returns a JWT token on success
    public function authJWT(Request $request, Response $response){
        //retrieve username and password from the request body
        $params= $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];

        //verify username and password
        $user = User::authenticateUser($username, $password);

        if(!$user){
            return $response->withJson(['Status' => 'Login Failed'], 401, JSON_PRETTY_PRINT);
        }

        //username and password are valid
        $jwt = User::generateJWT($user->id);
        $results = [
            'Status' => 'Login Successful',
            'jwt' => $jwt,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'role' => $user->role
        ];

        //return the results
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    }
}