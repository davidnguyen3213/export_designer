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
    public static function getMember( $arrMembers, $id_member ){
        foreach ($arrMembers as $key => $member_id) {
            if ($member_id->id == $id_member) {
                return $member_id->value->text;
            }
        }
    }
}
