<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Component.php
 * Description:
 */
namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
//The table associated with this model
    protected $table = 'component';

    //The primary key of the table
    protected $primaryKey = 'componentID';

    //The PK is numeric
    public $incrementing = true;

    //If the created_at and updated_at are not used
//    public $timestamps = false;

public function componentRecipes(){
    return $this->hasMany('NoMansSkyAPI\Models\ComponentRecipe', 'component');
}

public static function getComponents($request){
    $count = self::count();
    $params = $request->getQueryParams();

    //limit and offset
    $limit = array_key_exists('limit', $params)? (int) $params['limit'] :10;
    $offset = array_key_exists('offset', $params) ? (int) $params['offset'] : 0;

    //pagination
    $links = self::getLinks($request, $limit, $offset);

    //sorting
    $sort_key_array = self::getSortKeys($request);

    //build query
    $query = self::skip($offset)->take($limit);

    foreach ($sort_key_array as $column => $direction){
        $query->orderBy($column, $direction);
    }

    $component = $query->get();

    $results = [
        'totalCount' => $count,
        'limit' => $limit,
        'offset' => $offset,
        'links' => $links,
        'sort' => $sort_key_array,
        'data' => $component
    ];

    return $results;
}

    //Retrieve a specific component
    public static function getComponentByID(string $componentID) {
        $component = self::findOrFail($componentID);
        return $component;
    }

    // This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
        $count = self::count();

        // Get requet uri and parts
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
    //search components
    public static function searchComponents($term){
    $query = self::where('componentID', 'like', "%$term%")
        ->orWhere('componentName', 'like', "%$term%");
    return $query->get();
    }

    public static function createComponent($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new component instance
        $component = new Component();

        //set the component attributes
        foreach($params as $field => $value){
            $component->$field = $value;
        }

        //insert the component into the database
        $component->save();
        return $component;
    }

    //update a component
    public static function updateComponent($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('id');
        $component = self::find($id);
        if(!$component){
            return false;
        }

        //update attributes of the component
        foreach($params as $field => $value){
            $component -> $field = $value;
        }

        $component->save();
        return $component;
    }

    //delete a component
    public static function deleteComponent($request){
        //retrieve id from the request
        $id = $request->getAttribute('id');
        $component = self::find($id);
        return($component ? $component -> delete() : $component);
    }

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