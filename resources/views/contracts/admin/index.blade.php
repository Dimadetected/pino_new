@extends('layouts.app')
@section('content')
    <div class="row ">
        <div class="col-md-8 offset-md-2">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Договор</th>
                    <th>Контрагент</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contracts as $contract)
                 <tr class="">
                     <td><a href="{{route('contracts.show',$contract->id)}}">{{$contract->number}}</a></td>
                     <td>{{\Carbon\Carbon::parse($contract->date)->format('d.m.Y')}}</td>
                     <td><a class="" href="/{{$contract->file->src[0]}}">Файл</a></td>
                     <td><a href="{{route('clients.view',$contract->client_id)}}">{{$contract->client->name}}</a></td>
                     <td>@if($contract->status === (new \App\Models\Contract())::LAST_ID) Утверждено @else На утверждении @endif</td>
                 </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="col-md-1">
            <a href="{{route('contracts.form')}}" class="btn btn-success btn-block">Добавить</a>
        </div>
    </div>

@endsection
@section('script')
@endsection
