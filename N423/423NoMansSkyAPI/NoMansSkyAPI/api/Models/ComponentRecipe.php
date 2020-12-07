<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: ComponentRecipe.php
 * Description:
 */

namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentRecipe extends Model
{
protected $table = 'componentrecipes';

protected $primaryKey = 'recipeID';

public $incrementing = true;

  //  public $timestamps = false;


public function recipeComponentOne(){
    return $this->belongsTo('NoMansSkyAPI\Models\Component', 'compIngOne');
}

    public function recipeComponentTwo(){
        return $this->belongsTo('NoMansSkyAPI\Models\Component', 'compIngTwo');
    }

    public function recipeComponentThree(){
        return $this->belongsTo('NoMansSkyAPI\Models\Component', 'compIngThree');
    }

public function recipeResourceOne(){
    return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resIngOne');
}

    public function recipeResourceTwo(){
        return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resIngTwo');
    }

    public function recipeResourceThree(){
        return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resIngThree');
    }

    //Retrieve all componentRecipes
    public static function getComponentRecipes($request) {
    /************code for pagination*********/
    //get the number of row counts
    $count = self::count();

    //get querystring variables from URL
    $params = $request->getQueryParams();

    //do limit and offset exist
    $limit = array_key_exists('limit', $params) ? (int) $params['limit'] : 10; //items per pages
    $offset = array_key_exists('offset', $params) ? (int) $params['offset'] : 0; //offset of the first item

    //pagination
    $links = self::getLinks($request, $limit, $offset);

    //sorting
    $sort_key_array = self::getSortKeys($request);

    //build query
    $query = self::with(['component', 'resource'])->skip($offset)->take($limit); //limit the rows

    //sort the output by one or more columns
    foreach($sort_key_array as $column => $direction){
    $query->orderBy($column, $direction);
    }

    $componentRecipes = $query->get(); //run the query and get the results

    //construct the data for response
    $results = [
        'totalCount' => $count,
        'limit' => $limit,
        'offset' => $offset,
        'links' => $links,
         'sort' => $sort_key_array,
          'data' => $componentRecipes
    ];

    return $results;

    }

    public static function getComponentRecipeById(string $id){
    $query = self::with(['component', 'resource'])->findOrFail($id);
    return $query;
    }

    public static function searchComponentRecipes($term){
    $query = self::join('component', 'componentRecipe.component', '=', 'component.componentID')
        ->join('component', 'componentRecipe.compIngOne', '=', 'component.componentID')
        ->join('component', 'componentRecipe.compIngTwo', '=', 'component.componentID')
        ->join('component', 'componentRecipe.compIngThree', '=', 'component.componentID')
        ->join('resource', 'componentRecipe.resIngOne', '=', 'resource.resourceID')
        ->join('resource', 'componentRecipe.resIngTwo', '=', 'resource.resourceID')
        ->join('resource', 'componentRecipe.resIngThree', '=', 'resource.resourceID')
        ->where('componentRecipe.recipeID', 'like', "%$term%")
        ->orwhere('resource.resourceName', 'like', "%$term%");

    return $query->get();
    }
// This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
        $count = self::count();

        // Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();

        // Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . "/" . $path . "?limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];

        return $links;
    }


    public static function createComponentRecipe($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new componentRecipe instance
        $componentRecipe = new ComponentRecipe();

        //set the componentRecipe attributes
        foreach($params as $field => $value){
            $componentRecipe->$field = $value;
        }

        //insert the componentRecipe into the database
        $componentRecipe->save();
        return $componentRecipe;
    }

    //update a componentRecipe
    public static function updateComponentRecipe($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('id');
        $componentRecipe = self::find($id);
        if(!$componentRecipe){
            return false;
        }

        //update attributes of the componentRecipe
        foreach($params as $field => $value){
            $componentRecipe -> $field = $value;
        }

        $componentRecipe->save();
        return $componentRecipe;
    }

    //delete a componentRecipe
    public static function deleteComponentRecipe($request){
        //retrieve id from the request
        $id = $request->getAttribute('id');
        $componentRecipe = self::find($id);
        return($componentRecipe ? $componentRecipe -> delete() : $componentRecipe);
    }

    //sorting keys function
    private static function getSortKeys($request) {
        $sort_key_array = array();

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);  // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }
                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }
}