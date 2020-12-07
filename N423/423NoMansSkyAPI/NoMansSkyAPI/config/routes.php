<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: routes.php
 * Description:
 */

use NoMansSkyAPI\Authentication\{
    JWTAuthenticator
};

//define app routers
$app->get('/', function($request, $response, $args) {
    return $response->write("Welcome to No Man's Sky API!");
});


// User routes
$app->group('/api/v1/users', function () {
    $this->get('', 'User:index');
    $this->get('/{id}', 'User:view');
    $this->post('', 'User:create');
    $this->put('/{id}', 'User:update');
    $this->delete('/{id}', 'User:delete');
    $this->post('/authBearer', 'User:authBearer');
    $this->post('/authJWT', 'User:authJWT');
});

//Route group
$app->group('/api/v1', function() {
    //The Component group
    $this->group('/component', function() {
        $this->get('', 'Component:index');
        $this->get('/{componentID}', 'Component:view');
        $this->post('', 'Component:create');
        $this->put('/{id}', 'Component:update');
        $this->delete('/{id}', 'Component:delete');
    });

    //The uses group
    $this->group('/uses', function() {
        $this->get('', 'Uses:index');
        $this->get('/{usesID}', 'Uses:view');
    });

    //The Sources group
    $this->group('/sources', function() {
        $this->get('', 'Sources:index');
        $this->get('/{sourcesID}', 'Sources:view');
    });

    //The resource group
    $this->group('/resources', function() {
        $this->get('', 'Resource:index');
        $this->get('/{resourceID}', 'Resource:view');
        $this->post('', 'Resource:create');
        $this->put('/{id}', 'Resource:update');
        $this->delete('/{id}', 'Resource:delete');
    });

    //The Category group
    $this->group('/categories', function() {
        $this->get('', 'Category:index');
        $this->get('/{categoryID}', 'Category:view');
        $this->post('', 'Category:create');
        $this->put('/{id}', 'Category:update');
        $this->delete('/{id}', 'Category:delete');
    });

    //The ComponentRecipe group
    $this->group('/componentRecipes', function() {
        $this->get('', 'ComponentRecipe:index');
        $this->get('/{recipeID}', 'ComponentRecipe:view');
        $this->post('', 'ComponentRecipe:create');
        $this->put('/{id}', 'ComponentRecipe:update');
        $this->delete('/{id}', 'ComponentRecipe:delete');
    });

    //The refining group
    $this->group('/refining', function() {
        $this->get('', 'Refining:index');
        $this->get('/{refiningID}', 'Refining:view');
        $this->post('', 'Refining:create');
        $this->put('/{id}', 'Refining:update');
        $this->delete('/{id}', 'Refining:delete');
    });

    //The resourceUses group
    $this->group('/resourceUses', function(){
        $this->get('', 'ResourceUses:index');
        $this->get('/{resourceUsesID}', 'ResourceUses:view');
        $this->post('', 'ResourceUses:create');
        $this->put('/{id}', 'ResourceUses:update');
        $this->delete('/{id}', 'ResourceUses:delete');
    });

    //The resourceSources group
    $this->group('/resourceSources', function(){
        $this->get('', 'ResourceSources:index');
        $this->get('/{resourceSourcesID}', 'ResourceSources:view');
        $this->post('', 'ResourceSources:create');
        $this->put('/{id}', 'ResourceSources:update');
        $this->delete('/{id}', 'ResourceSources:delete');
    });


})->add(new JWTAuthenticator()); //JWT Authentication