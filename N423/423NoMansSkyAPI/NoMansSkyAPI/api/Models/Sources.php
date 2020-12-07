<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Sources.php
 * Description:
 */

namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Sources extends Model
{
//The table associated with this model
    protected $table = 'sources';

    //The primary key of the table
    protected $primaryKey = 'sourceID';

    //The PK is numeric
    public $incrementing = true;
   // public $timestamps = false;

    public function sources(){
        return $this->hasMany('NoMansSkyAPI\Models\ResourceSources','sourceID' );
    }

    //Retrieve all sources
    public static function getSources() {
        $sources = self::all();
        return $sources;
    }

    //Retrieve a specific source
    public static function getSourceByID(string $id) {
        $sources = self::findOrFail($id);
        return $sources;
    }
}