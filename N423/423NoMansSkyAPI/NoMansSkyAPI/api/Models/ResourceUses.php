<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: ResourceUses.php
 * Description:
 */

namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceUses extends Model
{
//The table associated with this model
    protected $table = 'resourceuses';

    //The primary key of the table
    protected $primaryKey = 'resourceUsesID';

    //The PK is numeric
    public $incrementing = true;

    //If the created_at and updated_at are not used
   // public $timestamps = false;

    public function sources(){
        return $this->belongsTo('NoMansSkyAPI\Models\Uses',  'usesID');
    }

    public function resources(){
        return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resourceID');
    }

    public static function getResourceUses(){
        $resourceUses = self::with(['resource'])->get();
        return $resourceUses;
    }

    public static function getResourceUsesById(string $id){
        return self::findOrFail($id);
    }

    //insert a new resourceUse
    public static function createResourceUses($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new resourceUse instance
        $resourceUses = new ResourceUses();

        //set the resourceUse attributes
        foreach($params as $field => $value) {
            $resourceUses->$field = $value;
        }

        //insert the resourceUse into the database
        $resourceUses->save();
        return $resourceUses;
    }

    //update a resourceUses
    public static function updateResourceUses($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('resourceUsesID');
        $resourceUses = self::find($id);
        if(!$resourceUses) {
            return false;
        }

        //update attributes of the resourceUse
        foreach ($params as $field => $value) {
            $resourceUses->$field = $value;
        }

        //save the resourceUse into the database
        $resourceUses->save();
        return $resourceUses;
    }

    //delete a resourceUses
    public static function deleteResourceUses($request) {
        //retrieve the id from the request
        $id = $request->getAttribute('resourceUsesID');
        $resourceUses = self::find($id);
        return($resourceUses ? $resourceUses->delete() : $resourceUses);
    }

}