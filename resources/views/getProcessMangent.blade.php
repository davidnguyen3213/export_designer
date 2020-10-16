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
            <form method="POST" action="{{route('getManagement.trello')}}">
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
                <button class="btn btn-success" type="submit">Lấy dữ liệu</button>
            </form>
        </div>
        @if (isset($message_error))
            <div class="alert alert-danger mt-3">{{ $message_error }}</div>
        @endif
        <div class="mt-3 mb-5">
            <table id="table_process" class="display table table-bordered">
                <thead>
                    <tr>
                        <th>List</th>
                        <th>Name</th>
                        <th>Member</th>
                        <th>Label</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Time Finish</th>
                        
                    </tr>
                </thead>
                <tbody>
                    
                    @if (isset($list_cards_process))
                        
                        @foreach ($list_cards_process as $process)
                            <tr>
                                <td>Processing</td>
                                <td>{{$process->name}}</td>
                                <td>{{App\Helpers\Helper::getNameMembers($list_members, $process->idMembers)}}</td>
                                <td>{{App\Helpers\Helper::getLabels($process->labels)}}</td>
                                <td>{{App\Helpers\Helper::getProirity($list_customFields[2], $process->customFieldItems)}}</td>
                                <td>{{App\Helpers\Helper::getTimeTrello($process->due)}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif

                    @if (isset($list_cards_review))
                        @foreach ($list_cards_review as $review)
                            <tr>
                                <td>Review</td>
                                <td>{{$review->name}}</td>
                                <td>{{App\Helpers\Helper::getNameMembers($list_members, $review->idMembers)}}</td>
                                <td>{{App\Helpers\Helper::getLabels($process->labels)}}</td>
                                <td>{{App\Helpers\Helper::getProirity($list_customFields[2], $process->customFieldItems)}}</td>
                                <td>{{App\Helpers\Helper::getTimeTrello($review->due)}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    @if (isset($list_cards_done))
                        @foreach ($list_cards_done as $done)
                            <tr>
                                <td>Done tháng 10</td>
                                <td>{{$done->name}}</td>
                                <td>{{App\Helpers\Helper::getNameMembers($list_members, $done->idMembers)}}</td>
                                <td>{{App\Helpers\Helper::getLabels($process->labels)}}</td>
                                <td>{{App\Helpers\Helper::getProirity($list_customFields[2], $process->customFieldItems)}}</td>
                                <td>{{App\Helpers\Helper::getTimeTrello($done->due)}}</td>
                                <td>{{App\Helpers\Helper::getTimeTrello($done->dateLastActivity)}}</td>
                            </tr>
                        @endforeach
                    @endif
                    
                </tbody>
            </table>
        </div>
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
        $('#table_process').DataTable({
            dom: 'Blfrtip',
            buttons: [
                'csv', 'excel'
            ],
            "order": [[0, 'desc']],
            "lengthMenu": [ 10, 25, 50, 75, 100 ],
            // "columnDefs" : [{"targets":0, "type":"date-eu"}],
        });
    </script>
@endsection