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

            //list imported
            $data_imported = [];
            $url_imported = "/1/boards/CpmGCmvs/cards";
            $getListImported = $client->request('GET', $url_imported, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'key' => $trello['key_app'],
                    'token' => $trello['token_app'],
                    "customFieldItems" => true,
                    "fields" => "idList,name"
                ],
            ]);
            $results_importerd = json_decode($getListImported->getBody());
            foreach($results_importerd as $key=>$imported){
                if($imported->idList == "5dce583a07de6f825e8be97f"){
                    array_push($data_imported, $imported);
                }
            }
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

            $url_list_member_imported = "/1/boards/CpmGCmvs/customFields";

            $getListMembers_imported = $client->request('GET', $url_list_member_imported, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'key' => $trello['key_app'],
                    'token' => $trello['token_app'],
                ],
            ]);
            $list_members_imported = json_decode($getListMembers_imported->getBody());
            return view("login")->with(compact("results", "list_members", "trello", "list_members_imported", "data_imported"));
        } catch (\Exception $e) {
            return view("login")->with("message_error", $e->getMessage());
        }

    }
}
