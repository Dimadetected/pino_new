@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <table class="table">
                <thead>
                <tr>
                    <th>Название</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($nets as $net)
                    <tr>
                        <td>{{$net->name}}</td>
                        <td class="text-right">
                            <a href="{{route(  $routes['form'],$net->id)}}" class="btn btn-primary">Редактировать</a>
                            <a href="{{route(  $routes['delete'],$net->id)}}" class="btn btn-danger">Удалить</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="row">

            </div>
        </div>
    </div>
@endsection
