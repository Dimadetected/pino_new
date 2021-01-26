@extends('layouts.app')



@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-8">
                <div class="card card-body shadow mb-1" style="min-height: 90vh">
                    <div class=" card-title" style="font-size: 16pt"><a href="#">Счет #{{$bill->id}}</a></div>
                    <div class=" card-title"
                         style="font-size: 16pt">{{\Carbon\Carbon::parse($bill->created_at)->format('d.m.Y')}}</div>
                    <div class="col-md-6 " style="font-size: 16pt">
                        <div class="row pl-0">
                            <div class="col-6 pl-0">Цепочка:</div>
                            <div class="col-6 pl-0">{{$bill->chain->name}}</div>
                            @if(isset($bill->client))
                                <div class="col-6 pl-0">Контрагент:</div>
                                <div class="col-6 pl-0">{{$bill->client->name}}</div>
                            @endif
                            @if(isset($bill->number))
                                <div class="col-6 pl-0">Номер:</div>
                                <div class="col-6 pl-0">{{$bill->number}}</div>
                            @endif
                            @if(isset($bill->date))
                                <div class="col-6 pl-0">Дата:</div>
                                <div class="col-6 pl-0">{{$bill->date}}</div>
                            @endif
                            @if(isset($bill->sum))
                                <div class="col-6 pl-0">Сумма:</div>
                                <div class="col-6 pl-0">{{$bill->sum}}</div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 mb-2  h4">{{$bill->bill_type->name??'Оплачено'}}</div>
                    <hr>
                    <p class="my-3">{{$bill->text}}</p>
                    @if(isset($bill->file))
                        @foreach($bill->file->src as $file)
                            <a href="/{{$file}}" target="_blank" class="btn btn-primary">Файл</a>

                            @php($explode = explode('.',$file))
                            @if(in_array(array_pop($explode),['pdf','doc','docx','excel','xls']))
                                <embed src="/{{$file}}" class="mt-1   files{{$bill->id}}" type="application/pdf"
                                       height="500px" width="100%">
                            @else
                                <img src="/{{$file}}" alt="" class="mt-1   files{{$bill->id}}" style="width: 100%;">
                            @endif

                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">

                <div class="card card-body shadow mb-5">
                    @if($bill->steps ==0  and $bill->user_id == auth()->user()->id)
                        <a href="{{route('bill.delete',$bill->id)}}" class="btn btn-danger my-1">Удалить</a>
                    @endif
                    {{--                    <a target="_blank" href="{{route('bill.print',$bill->id)}}" class="btn btn-primary" >Распечатать</a>--}}
                    <a onclick="print()" class="btn btn-primary">Распечатать</a>
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
                    @elseif(($bill->user_role_id == $user->user_role_id ) or
                                ($bill->steps == 0 and $bill->user_id == auth()->user()->id) or
                                ($bill->user_role_id == $user->user_role_id and $bill->user_role_id == 4))
                        <bill-status-change-component :bill="{{$bill}}"></bill-status-change-component>
                    @endif
                    @if(isset($bill->bill_action->user_id) and $bill->bill_action->user_id == $user->id)
                        <a href="{{route('bill.back',$bill->id)}}" class="btn btn-dark mt-3">Изменить</a>
                    @endif
                    <bill-actions :messages="{{$bill->messages}}" :actions="{{$bill->bill_actions}}"></bill-actions>
                    <hr class="mt-4">
                    <div class="mt-3">
                        <message-create :user_id="'{{auth()->user()->id}}'" :type="'bill'"
                                        external_id="{{(int)$bill->id}}"></message-create>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function print() {
            window.open('/{{$print_file}}').print();
        }
    </script>
@endsection
