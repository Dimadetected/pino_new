@extends('layouts.app')



@section('content')

    <div class="container">
        <div class="row ">
            <div class="col-md-8">
                <div class="card card-body shadow mb-1" style="min-height: 90vh">
                    <div class=" card-title" style="font-size: 16pt"><a href="#">Счет #{{$bill->id}}</a></div>
                    <div class=" card-title" style="font-size: 16pt">{{\Carbon\Carbon::parse($bill->created_at)->format('d.m.Y')}}</div>
                    <div class="my-2  h4">{{$bill->bill_type->name??'Оплачено'}}</div>
                    <hr>
                    <p class="my-3">{{$bill->text}}</p>
                    @if(isset($bill->file))
                        @foreach($bill->file->src as $file)
                            @if(explode('.',$file)[1] == 'pdf')
                                <embed src="/{{$file}}" class="mt-1 d-none  files{{$bill->id}}" type="application/pdf" height="400px" width="100%">
                            @else
                                <img src="/{{$file}}" alt="" class="img-fluid">
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">

                <div class="card card-body shadow mb-5">
                    <div class=" card-title" style="font-size: 16pt">История взаимодействия:</div>
                    <hr>
                    @foreach($bill->bill_actions as $key => $bill_action)
                        <p class="my-3">{{$bill_action->user->name}}:<br>
                            <span class="pl-4">{{$bill_action->text}}</span>
                        <div class="text-right">{{\Carbon\Carbon::parse($bill_action->created_at)->format('d.m.Y H:i')}}</div>
                        </p>
                        @if($key != count($bill->bill_actions) -1 )
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
