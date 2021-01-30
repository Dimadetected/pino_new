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
                                <option @if($bill->chain_id == $chain->id) selected
                                        @endif value="{{$chain->id}}">{{$chain->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="number">Номер:</label>
                        <input type="text" name="number" id="number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">Дата:</label>
                        <input type="text" name="date" id="date" placeholder="дд.мм.гггг" class="form-control"
                               onkeyup="dateCheck()">
                    </div>
                    <div class="form-group">
                        <label for="client_id">Контрагент:</label>
                        <select name="client_id" id="client_id" class="form-control">
                            @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sum">Сумма:</label>
                        <input type="text" name="sum" id="sum" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="text">Описание счета:</label>
                        <textarea name="text" id="text" cols="30" rows="5"
                                  class="form-control @error('text') is-invalid @enderror"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="exampleFormControlFile1">Выберите файлы</label>
                        <input type="file" name="files[]" class="form-control-file" multiple
                               id="exampleFormControlFile1">
                    </div>

                    <button class="btn btn-success mt-3">Создать</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
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

        function changeCookies() {
            let val = document.getElementById('chain_id').value
            console.log(val)
            document.cookie = 'chain_id=' + val
            // console.log(document.cookie = 'chain_id=' + val)
        }
    </script>
@endsection
