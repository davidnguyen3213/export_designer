<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GetDataProcessManagent extends Controller
{
    public function index()
    {
        $trello = Session::get("trello");
        return view("getProcessMangent")->with(compact("trello"));
    }
    public function getDataTrello(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key_app' => 'required',
            'token_app' => 'required',
        ])->validate();
        $trello = [
            "token_app" => $request->token_app,
            "key_app" => $request->key_app,
            "id_board" => "m1O4A3hx",
            "id_list_process" => "5e65f7b064acdc64a9e678cf",
            "id_list_review" => "5e65f7b064acdc64a9e678d0",
            "id_list_done" => "5f75866d6db44e833dbb64ad"
        ];
        Session::put("trello", $trello);
        //get list member
        $url_list_member = "/1/boards/" . $trello['id_board'] . "/members";
        $query_list = [
            'key' => $trello['key_app'],
            'token' => $trello['token_app'],
            "customFieldItems" => true,
        ];
        $list_members = Helper::callApiTrello($trello, $query_list, $url_list_member);
        //list cards process
        $url_cards_process = "/1/lists/". $trello['id_list_process'] ."/cards";
        $list_cards_process = Helper::callApiTrello($trello, $query_list, $url_cards_process);
        //list cards pending
        $url_cards_review = "/1/lists/" . $trello['id_list_review'] . "/cards";
        $list_cards_review = Helper::callApiTrello($trello, $query_list, $url_cards_review);
        //list cards done
        $url_cards_done = "/1/lists/" . $trello['id_list_done'] . "/cards";
        $list_cards_done = Helper::callApiTrello($trello, $query_list, $url_cards_done);
        //list custom field
        $url_list_customFields = "/1/boards/". $trello['id_board'] . "/customFields";
        $list_customFields = Helper::callApiTrello($trello, $query_list, $url_list_customFields);
        //list labels
        $url_list_labels = "/1/boards/" . $trello['id_board'] . "/labels";
        $list_labels = Helper::callApiTrello($trello, $query_list, $url_list_labels);

        return view("getProcessMangent")->with(compact("list_members", "list_cards_process", "list_cards_review", "list_cards_done", "list_customFields", "list_labels"));
    }
}