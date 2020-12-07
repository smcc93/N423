<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Category.php
 * Description:
 */

namespace NoMansSkyAPI\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected  $table = 'category';

    protected $primaryKey = 'categoryID';

    public  $incrementing = true;

//    public $timestamps = false;

    public function resources(){
        return $this->hasMany('NoMansSkyAPI\Models\Resource', 'categoryID');
    }

    //retrieve all categories
    public static function getCategories(){
        $categories = self::all();
        return $categories;
    }
    
    //retrieve a specific category
    public static function getCategoryByID(string $categoryID){
        $category = self::findOrFail($categoryID);
        return $category;
    }

}