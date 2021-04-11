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
                @foreach($clients as $client)
                    @foreach($client->file_id as $file)
                        <tr>
                            <td>{{$file['numb'] != ""?$file['numb']:"Нет"}}</td>
                            <td>{{$file['date'] != ""?\Carbon\Carbon::parse($file['date'])->format('d.m.Y'):"Нет"}}</td>
                            <td><a href="/{{$files[$file['file_id']][0]??""}}" class="btn btn-primary"
                                   target="_blank">Договор</a>
                            </td>
                            <td><a href="{{route('clients.view',$client->id)}}" class="btn btn-primary"
                                   target="_blank">{{$client->name}}</a></td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection
@section('script')
@endsection
