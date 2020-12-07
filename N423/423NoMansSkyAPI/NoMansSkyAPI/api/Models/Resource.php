<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Resource.php
 * Description:
 */


namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
protected $table = 'resource';
protected $primaryKey = 'resourceID';
public $incrementing = true;
   // public $timestamps = false;

public function resourceRefining(){
    return $this->hasMany('NoMansSkyAPI\Models\Refining', 'mainResource');
}

//Retrieve all resources
    public static function getResources($request) {

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
        $query = self::skip($offset)->take($limit); //limit the rows

        //sort the output by one or more columns
        foreach($sort_key_array as $column => $direction){
            $query->orderBy($column, $direction);
        }

        $resources = $query->get(); //run the query and get the results

        //construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $resources
        ];

        return $results;
    }
    //Retrieve a specific resource
    public static function getResourceByID(string $id) {
        $resource = self::findOrFail($id);
        return $resource;
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

    //Search for resource
    public static function searchResources($term){
        $query = self::where('resourceID', 'like', "%$term%")
            -> orWhere('resourceName', 'like', "%$term%");

        return $query->get();
    }

    public static function createResource($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new resource instance
        $resource = new Resource();

        //set the resource attributes
        foreach($params as $field => $value){
            $resource->$field = $value;
        }

        //insert the resource into the database
        $resource->save();
        return $resource;
    }
    //update a resource
    public static function updateResource($request){
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('id');
        $resource = self::find($id);
        if(!$resource){
            return false;
        }

        //update attributes of the ingredient
        foreach($params as $field => $value){
            $resource -> $field = $value;
        }

        $resource->save();
        return $resource;
    }

    //delete a resource
    public static function deleteResource($request){
        //retrieve id from the request
        $id = $request->getAttribute('id');
        $resource = self::find($id);
        return($resource ? $resource -> delete() : $resource);
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