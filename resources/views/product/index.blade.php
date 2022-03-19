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
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->name}}</td>
                        <td class="text-right">
                            <a href="{{route(  $routes['form'],$product->id)}}" class="btn btn-primary">Редактировать</a>
                            <a href="{{route(  $routes['delete'],$product->id)}}" class="btn btn-delete">Удалить</a>
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
