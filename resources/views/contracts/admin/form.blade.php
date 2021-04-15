@extends('layouts.app')



@section('content')
    <div class="row ">
        <form class="col-12" action="{{route('contracts.store',$contract)}}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="card card-body">
                        <div class="form-group">
                            <div class="h2">Добавление договора:</div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <label for="name">Номер:</label>
                            <input type="text" id="number" name="number" value="{{$contract->number}}"
                                   class="form-control @error('number') is-invalid @enderror">
                        </div>
                        <div class="form-group">
                            <label for="date">Дата:</label>
                            <input type="text" id="date" placeholder="дд.мм.гггг" name="date"
                                   value="{{\Carbon\Carbon::parse($contract->date)->format('d.m.Y')}}"
                                   class="form-control @error('date') is-invalid @enderror">
                        </div>
                        <div class="form-group">
                            <label for="client_id">Контрагент:</label>
                            <select name="client_id" id="client_id" class="form-control">
                                @foreach($clients as $id => $client)
                                    <option
                                        @if((old('text') and old('client_id') == $id) or ($contract->client_id == $id)) selected
                                        @endif value="{{$id}}">{{$client}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3 @error('files') is-invalid @enderror">
                            <label for="exampleFormControlFile1">Выберите файлы</label>
                            <input type="file" name="files" class="form-control-file"
                                   id="exampleFormControlFile1">
                        </div>
                    </div>
                    <input type="text" hidden name="type" value="{{$btn == "Утвердить"?1:0}}">
                    <button class="btn btn-block btn-success mt-3">{{$btn}}</button>
                </div>

            </div>
        </form>
    </div>

@endsection
@section('script')
    <script>
        $('#date').mask("00.00.0000")
    </script>
@endsection
