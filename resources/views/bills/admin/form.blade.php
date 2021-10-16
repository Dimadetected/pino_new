@extends('layouts.app')



@section('content')
    <div class="row ">
        <div class="col-md-4 offset-md-4">
            <form action="{{route($routes['store'],$bill)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-body">
                    <div class="form-group">
                        <div class="h2">Создание счета:</div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="chain_id">Цепочка:</label>
                        <select name="chain_id" id="chain_id" class="form-control" onchange="changeCookies()">
                            @foreach($chains as $chain)
                                <option @if($bill->chain_id == $chain->id) selected @elseif(old('chain_id') and old('chain_id') == $chain->id) selected
                                        @endif value="{{$chain->id}}">{{$chain->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="number">Номер:</label>
                        <input type="text" name="number" id="number" class="form-control" value="{{old('number')}}">
                    </div>
                    <div class="form-group">
                        <label for="date">Дата:</label>
                        <input type="text" name="date" id="date" placeholder="дд.мм.гггг" class="form-control" value="{{old('date')}}"
                               onkeyup="dateCheck()">
                    </div>
                    <div class="form-group">
                        <label for="client_id">Контрагент:</label>
                        <select name="client_id" id="client_id" class="form-control js-example-basic-single">
                            @foreach($clients as $client)
                                <option @if(old('text') and old('client_id') == $client->id) selected @endif value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sum">Сумма:</label>
                        <input type="text" name="sum" id="sum" value="{{old('sum')}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="text">Описание счета:</label>
                        <textarea name="text" id="text" cols="30" rows="5"
                                  class="form-control @error('text') is-invalid @enderror">{{old('text')}}</textarea>
                    </div>
                    <div class="form-group mt-3 @error('files') is-invalid @enderror">
                        @error('badVersionFile')
                        <div class="alert alert-danger">
                            Данный файл имеет неподдерживаемую версию.<br> Для того, чтобы файл был успешно обработан вам необходимо загрузить его на
                            <a target="_blank" href="https://online2pdf.com/convert-pdf-to-pdf">сайт</a> и полученный файл загрузить сюда.
                        </div>
                        @enderror
                        @error('files')
                        <div class="alert alert-danger">
                            Необходимо загрузить файл PDF формата
                        </div>
                        @enderror
                        <label for="exampleFormControlFile1">Выберите файлы</label>
                        <input type="file" name="files" class="form-control-file" multiple
                               id="exampleFormControlFile1">
                    </div>

                    <button class="btn btn-success mt-3">Создать</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#date').mask('00.00.0000');
        // function dateCheck() {
        //     let val = document.getElementById('date').value;
        //     if ((val.length == 2) || (val.length == 5))
        //         document.getElementById('date').value = val + '.'
        //     if (val.length > 10) {
        //         document.getElementById('date').value = val.substring(0, 10)
        //     }
        // }
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        function changeCookies() {
            let val = document.getElementById('chain_id').value
            console.log(val)
            document.cookie = 'chain_id=' + val
            // console.log(document.cookie = 'chain_id=' + val)
        }
    </script>
@endsection
