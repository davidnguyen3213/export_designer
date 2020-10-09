<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
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
        
        $trello = [
            "token_app" => $request->token_app,
            "key_app" => $request->key_app,
            "id_board" => $request->id_board,
        ];
        Session::put("trello", $trello);
        
        try {
            $url_list_card = "/1/boards/" . $trello['id_board'] . "/cards";
            $query = [
                'key' => $trello['key_app'],
                'token' => $trello['token_app'],
                "customFieldItems" => true,
                "fields" => "name"
            ];
            $results = Helper::callApiTrello($trello, $query, $url_list_card);
            
            $url_list_member = "/1/boards/". $trello['id_board'] . "/customFields";
            $query_list = [
                'key' => $trello['key_app'],
                'token' => $trello['token_app'],
            ];
            $list_members = Helper::callApiTrello($trello, $query_list, $url_list_member);
            return view("login")->with(compact("results", "list_members", "trello"));
        } catch (\Exception $e) {
            return view("login")->with("message_error", $e->getMessage());
        }

    }
}
