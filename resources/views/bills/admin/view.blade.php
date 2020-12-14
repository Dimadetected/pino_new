@extends('layouts.app')



@section('content')

    <div class="container" >
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
                                <embed  src="/{{$file}}" class="mt-1   files{{$bill->id}}" type="application/pdf" height="500px" width="100%">
                            @else
                                <img src="/{{$file}}" alt="" class="mt-1   files{{$bill->id}}" style="width: 100%;">
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">

                <div class="card card-body shadow mb-5">
                    <button class="btn btn-primary" onclick="print()">Распечатать</button>
                    @if($bill->status == 2)
                        <div class="row ">
                            <div class="col-12 mt-2 text-center ">
                                <hr>
                                <div class="h4 mt-2" role="group" aria-label="Basic example">
                                    Отказано
                                </div>
                            </div>
                        </div>
                    @elseif($bill->status == 1 and is_null($bill->user_role_id))
                        <div class="row">
                            <div class="col-12 mt-2 text-center ">
                                <hr>
                                <div class="h4 mt-2" role="group" aria-label="Basic example">
                                    Оплачено
                                </div>
                            </div>
                        </div>
                    @elseif(($bill->user_role_id == $user->user_role_id and !in_array($bill->user_role_id,[4])) or ($bill->user_role_id == 6 and $bill->user_id == auth()->user()->id))
                        <div class="row">
                            <div class="btn-group col-12" role="group" aria-label="Basic example">
                                <a href="{{route('bill.consult',['bill' => $bill,'type' => 'accept'])}}" class="btn btn-block btn-success my-1 text-light">Утвердить</a>
                                <a href="{{route('bill.consult',['bill' => $bill,'type' => 'decline'])}}" class="btn btn-block btn-danger my-1 text-light">Отказать</a>
                            </div>
                        </div>
                    @elseif($bill->user_role_id == $user->user_role_id and $bill->user_role_id == 4)
                        <div class="row">
                            <div class="btn-group col-12" role="group" aria-label="Basic example">
                                <a href="{{route('bill.consult',['bill' => $bill,'type' => 'accept'])}}" class="btn btn-block btn-warning text-light my-1">Оплатить</a>
                            </div>
                        </div>
                    @endif
                    <div class=" card-title mt-4" style="font-size: 16pt">История взаимодействия:</div>
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
    <script>
        function print(){
            window.open('/{{$file}}').print();
        }
    </script>
@endsection
