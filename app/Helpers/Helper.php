<?php

namespace App\Helpers;

use GuzzleHttp;
use Carbon\Carbon;
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
    public static function callApiTrello($trello, $query, $url){
        $info_trello = "https://api.trello.com";
        $client = new GuzzleHttp\Client(['base_uri' => $info_trello]);
        $getListCards = $client->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $query,
        ]);
        $results = json_decode($getListCards->getBody());
        return $results;
    }
    public static function getNameMembers($list_members, $idMembers){
        $member_name = "";
        foreach($idMembers as $key=>$id_member){
            foreach($list_members as $key_2=>$member){
                if($member->id == $id_member){
                    $member_name .= $member->fullName .",";
                }
            }
        }
        return $member_name;
    }
    public static function getTimeTrello($time_trello = ""){
        if($time_trello != ""){
            $time = [
                "time" => substr($time_trello, 11, 8),
                "date" => substr($time_trello, 0, 10)
            ];
            return Carbon::parse($time["date"] . " " . $time["time"])->addHours(7)->format('d/m/Y H:i:s');
        }
        return null;
        //return Carbon::parse($time["date"] . " " . $time["time"])->addHours(7)->format('d/m/Y H:i:s');
    }
}
