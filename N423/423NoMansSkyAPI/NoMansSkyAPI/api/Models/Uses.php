<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Uses.php
 * Description:
 */


namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;
class Uses extends Model
{
protected $table = 'uses';
protected $primaryKey = 'usesID';
public $incrementing = true;
   // public $timestamps = false;


public function uses(){
    return $this->hasMany('NoMansSkyAPI\ResourceUses', 'usesID');
}
    //Retrieve all uses
    public static function getUses() {
        $uses = self::all();
        return $uses;
    }

    //Retrieve a specific use
    public static function getUseByID(string $id) {
        $uses = self::findOrFail($id);
        return $uses;
    }

}