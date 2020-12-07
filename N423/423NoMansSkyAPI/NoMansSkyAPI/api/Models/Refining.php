<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Refining.php
 * Description:
 */
namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Refining extends Model
{
    protected $table = 'refining';

    protected $primaryKey = 'refiningID';

    public $incrementing = true;

    public function mainResource(){
        return $this->belongsTo('NoMansSkyAPI\Models\Resource', 'resourceID');
    }

    public static function getRefining(){
        $refining = self::with(['resource'])->get();
        return $refining;
    }

    public static function getRefiningById(string $id){
        return self::findOrFail($id);
    }

    //insert a new refining
    public static function createRefining($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //create a new refining instance
        $refining = new Refining();

        //set the refining attributes
        foreach($params as $field => $value) {
            $refining->$field = $value;
        }

        //insert the refining into the database
        $refining->save();
        return $refining;
    }

    //update a refining
    public static function updateRefining($request) {
        //retrieve parameters from request body
        $params = $request->getParsedBody();

        //retrieve id from the request body
        $id = $request->getAttribute('resourceID');
        $refining = self::find($id);
        if(!$refining) {
            return false;
        }

        //update attributes of the refining
        foreach ($params as $field => $value) {
            $refining->$field = $value;
        }

        //save the refining into the database
        $refining->save();
        return $refining;
    }

    //delete a refining
    public static function deleteRefining($request) {
        //retrieve the id from the request
        $id = $request->getAttribute('resourceID');
        $refining = self::find($id);
        return($refining ? $refining->delete() : $refining);
    }
}