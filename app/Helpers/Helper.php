<?php

namespace App\Helpers;


class Helper
{
    public function __construct()
    {
        exit('Init function is not allowed');
    }
    public static function getValueFirst($string,$symbol)
    {
        $array = explode($symbol, $string);
        return $array[0];
    }
    public static function checkRole($member_ids, $list_members){
        $role = [
            "ds" => "",
            "mo" => "",
            "id" => ""
        ];
        foreach( $member_ids as $member_id ){
            $name = self::checkIssetMember($member_id, $list_members);
            $code = self::getValueFirst($name["username"], "_");
            switch ($code) {
                case 'ds':
                    $role["ds"] = $name["fullname"];
                    break;
                case 'mo':
                    $role["mo"] = $name["fullname"];
                    break;
                case 'id':
                    $role["id"] = $name["fullname"];
                    break;
                default:
                    
                    break;
            }
        }
        return $role;
    }
    private static function checkIssetMember($member_id, $list_members){
        foreach($list_members as $member){
            if($member_id == $member->id){
                return $name = [
                    "username" => $member->username,
                    "fullname" => $member->fullName
                ];
            }
        }
        return false;

    }
}
