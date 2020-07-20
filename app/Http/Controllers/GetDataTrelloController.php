<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GetDataTrelloController extends Controller
{
    public function indexTool(){
        $trello = Session::get("trello");
        return view("login")->with(compact("trello"));
    }
    public function getDataTrello(Request $request){
        $validator = Validator::make($request->all(), [
            'id_board' => 'required',
            'key_app' => 'required',
            'token_app'=> 'required',
        ])->validate();
        $info_trello = "https://api.trello.com";
        $trello = [
            "token_app" => $request->token_app,
            "key_app" => $request->key_app,
            "id_board" => $request->id_board,
        ];
        Session::put("trello", $trello);
        try {
            $client = new GuzzleHttp\Client(['base_uri' => $info_trello]);
            $url_list_card = "/1/boards/". $trello['id_board'] ."/cards";
            $getListCards = $client->request('GET', $url_list_card, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'key' => $trello['key_app'],
                    'token' => $trello['token_app'],
                    "customFieldItems" => true,
                    "fields" => "name"
                ],
            ]);
            $results = json_decode($getListCards->getBody());
            
            $url_list_member = "/1/boards/". $trello['id_board'] . "/customFields";

            $getListMembers = $client->request('GET', $url_list_member, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'key' => $trello['key_app'],
                    'token' => $trello['token_app'],
                ],
            ]);
            $list_members = json_decode($getListMembers->getBody());
            return view("login")->with(compact("results", "list_members", "trello"));
        } catch (\Exception $e) {
            return view("login")->with("message_error", $e->getMessage());
        }

    }
}
