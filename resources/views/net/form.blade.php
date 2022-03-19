@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <form action="/nets" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Название:</label>
                    <input type="text" hidden name="id" value="{{$net->id??0}}">
                    <input type="text" name="name" class="form-control" value="{{old("name")??($net->name??"")}}">
                </div>
                <button class="btn btn-success">Сохранить</button>
            </form>
        </div>
    </div>
@endsection
