<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: ResourceSources.php
 * Description:
 */
namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceSources extends Model
{
//The table associated with this model
    protected $table = 'resourceSources';

    //The primary key of the table
    protected $primaryKey = 'resourceSourcesID';

    //The PK is numeric
    public $incrementing = true;

    //If the created_at and updated_at are not used
    // public $timestamps = false;

    public function sources(){
        return $this->belongsTo('NoMansSkyAPI\Models\Sources',  'sourcesID');
    }

    public function resources(){
        return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resourceID');
    }

    public static function getResourceSources(){
        $resourceUses = self::with(['resource'])->get();
        return $resourceUses;
    }

    public static function getResourceSourcesById(string $id){
        return self::findOrFail($id);
    }

    //insert a new resourceSource
    public static function createResourceSources($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new resourceSource instance
        $resourceSource = new ResourceSources();

        //set the resourceSource attributes
        foreach($params as $field => $value) {
            $resourceSource->$field = $value;
        }

        //insert the resourceSource into the database
        $resourceSource->save();
        return $resourceSource;
    }

    //update a resourceSource
    public static function updateResourceSources($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('resourceUsesID');
        $resourceSource = self::find($id);
        if(!$resourceSource) {
            return false;
        }

        //update attributes of the resourceSource
        foreach ($params as $field => $value) {
            $resourceSource->$field = $value;
        }

        //save the resourceSource into the database
        $resourceSource->save();
        return $resourceSource;
    }

    //delete a resourceSource
    public static function deleteResourceSources($request) {
        //retrieve the id from the request
        $id = $request->getAttribute('resourceUsesID');
        $resourceSource = self::find($id);
        return($resourceSource ? $resourceSource->delete() : $resourceSource);
    }
}