<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Token.php
 * Description:
 */

namespace NoMansSkyAPI\Models;

use Illuminate\Database\Eloquent\Model;
class Token extends Model
{
//lifetime of the Bearer token: seconds

    const EXPIRE = 3600;

    //generate a Bearer token if it does not exist for current user and store token in database
    //if token exists and has not expired, retrieve the token from the database

    public static function generateBearer($id){
        //attempt to retrieve the token by user id
        $token = self::where('user', $id)->first();

        //determine a time in the past: current time minus lifetime of the token
        $expire = time() - self::EXPIRE;

        //if the token exists and has expired, create a new one
        if($token) {
            if($expire > date_timestamp_get($token->updated_at)){
                $token->value = bin2hex(random_bytes(64));
                $token->save();
            }
            return $token;
        }

        //a token does not exist; create a new one
        $token = new Token();
        $token->user = $id;
        $token->value = bin2hex(random_bytes(64));
        $token->save();

        return $token;
    }

    //validate a Bearer token by matching the token with a database record
    public static function validateBearer($value){
        //retrieve the token from the database
        $token = self::where('value', $value)->first();

        //create a time in the past
        $expire = time() - self::EXPIRE;

        return ($token && $expire < date_timestamp_get($token->updated_at)) ? $token : false;

    }
}