<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: services.php
 * Description:
 */

use NoMansSkyAPI\Controllers\CategoryController;
use NoMansSkyAPI\Controllers\componentController;
use NoMansSkyAPI\Controllers\ComponentRecipeController;
use NoMansSkyAPI\Controllers\refiningController;
use NoMansSkyAPI\Controllers\resourceController;
use NoMansSkyAPI\Controllers\ResourceSourcesController;
use NoMansSkyAPI\Controllers\ResourceUsesController;
use NoMansSkyAPI\Controllers\SourcesController;
use NoMansSkyAPI\Controllers\UsesController;
use NoMansSkyAPI\Controllers\UserController;

$container['Category'] = function ($c){
    return new CategoryController();
};

$container['Component'] = function ($c){
    return new componentController();
};

$container['ComponentRecipe'] = function ($c){
    return new ComponentRecipeController();
};

$container['Refining'] = function ($c){
    return new refiningController();
};

$container['Resource'] = function ($c){
    return new resourceController();
};

$container['ResourceSources'] = function ($c){
    return new ResourceSourcesController();
};

$container['ResourceUses'] = function ($c){
    return new ResourceUsesController();
};

$container['Sources'] = function ($c){
    return new SourcesController();
};

$container['Uses'] = function ($c){
    return new UsesController();
};

$container['User'] = function ($c){
    return new UserController();
};