@extends('index')
@section('title')
    The Human Inc Tool
@endsection
@section('content')
    <div class="container">
        <header class="text-center mt-3 mb-3">
            <h2>THE HUMAN INC</h2>
        </header>
        <div class="">
            <form method="POST" action="{{route('getList.trello')}}">
                @csrf
                <div class="form-group">Lấy API key app Trello: <a target="_blank" href="https://trello.com/app-key">https://trello.com/app-key</a></div>
                <div class="form-group">
                    <label>Nhập API key:</label>
                    <input name="key_app" autocomplete="off" id="api-key" class="form-control" type="text" value="{{ isset($trello['key_app']) ? $trello['key_app'] : '' }}">
                    @error('key_app')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">Lấy token đăng nhập: <a target="_blank" id="link_get_token" href="#">link</a></div>
                <div class="form-group">
                    <label>Nhập Token:</label>
                    <input name="token_app" autocomplete="off" id="token" class="form-control" type="text" value="{{ isset($trello['token_app']) ? $trello['token_app'] : '' }}">
                    @error('token_app')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Nhập ID của board:</label>
                    <input name="id_board" autocomplete="off" class="form-control" type="text" value="{{ isset($trello['id_board']) ? $trello['id_board'] : '' }}">
                    @error('id_board')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-success" type="submit">Lấy dữ liệu</button>
            </form>
        </div>
        @if (isset($message_error))
            <div class="alert alert-danger mt-3">{{ $message_error }}</div>
        @endif
        @if (isset($results))
        <div class="mt-3 mb-5">
            <table id="table_id" class="display table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Code</th>
                        <th>Idea</th>
                        <th>Designer</th>
                        <th>Mockup</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        @foreach ($result->cards as $card)
                            <tr>
                                <td>{{ str_replace(".","/",$result->name) }}</td>
                                <td>{{ \App\Helpers\Helper::getValueFirst($card->name,"-") }}</td>
                                @php
                                    $role_member = \App\Helpers\Helper::checkRole($card->idMembers, $list_members);
                                    $designer = ( $role_member['ds'] != "" ) ? $role_member['ds'] : ( $role_member['mo'] != "" ? $role_member['mo'] : "Unknow" ) ;
                                    $mockup = ( $role_member['mo'] != "" ) ? $role_member['mo'] : ( $role_member['ds'] != "" ? $role_member['ds'] : "Unknow" ) ;
                                @endphp
                                <td>
                                    {{  ($role_member['id'] != "") ? $role_member['id'] : "Unknow" }}    
                                </td>
                                <td>
                                    {{ $designer  }}    
                                </td>
                                <td>
                                   
                                    {{ $mockup }}    
                                </td>
                            </tr>
                        @endforeach
                            
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>    
@endsection
@section('js_page')
    <script>
        "use strict"
        $("#api-key").on("change",function(){
            let api_key = $(this).val();
            let url = "https://trello.com/1/authorize?expiration=never&name=MyPersonalToken&scope=read&response_type=token&key=" + api_key;
            $("#link_get_token").attr("href", url);
        })
        $('#table_id').DataTable({
            dom: 'Blfrtip',
            buttons: [
                'csv', 'excel'
            ],
            "order": [[0, 'desc']],
            "lengthMenu": [ 10, 25, 50, 75, 100 ],
            "columnDefs" : [{"targets":0, "type":"date-eu"}],
        });
    </script>
@endsection