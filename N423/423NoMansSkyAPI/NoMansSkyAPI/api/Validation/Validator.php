<?php
/**
 * Author: Stewart McCalley
 * Date: 12/4/2020
 * File: Validator.php
 * Description:
 */
namespace NoMansSkyAPI\Validation;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;
class Validator
{
    private static $errors = [];

    //generic validation method. true on success or false on failure
    public static function validate($request, array $rules){
        foreach ($rules as $field => $rule) {
            //retrieve parameter from URL or the request body
            $param = $request->getAttribute($field) ?? $request->getParam($field);
            try{
                $rule->setName(ucfirst($field))->assert($param);
            }catch(NestedValidationException $ex){
                self::$errors[$field] = $ex->getMessage();
            }

        }

        return empty(self::$errors);
    }

    // Validate attributes of a User model. Do not include fields having default values (id, role, etc.)
    public static function validateUser($request) {
        $rules = [
            'firstname' => v::alnum(' '),
            'lastname' => v::alnum(' '),
            'email' => v::email(),
            'username' => v::notEmpty(),
            'password' => v::notEmpty()
        ];

        return self::validate($request, $rules);
    }

    //return the errors in an array
    public static function getErrors(){
        return self::$errors;
    }
}