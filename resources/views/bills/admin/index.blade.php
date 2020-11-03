@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-12">
                <div class="row">
                    @foreach($bills as $bill)
                        <div class="col-md-4 my-2">
                            <div class="card card-body shadow mb-5">
                                <div class=" card-title" style="font-size: 16pt"><a class="text-primary" href="{{route($routes['view'],$bill)}}">Счет #{{$bill->id}}</a></div>
                                <div class=" card-title" style="font-size: 16pt">{{\Carbon\Carbon::parse($bill->created_at)}}</div>
                                <div class="my-2  h4">{{$bill->bill_type->name}}
                                </div>
                                <hr>
                                <p class="my-3">{{$bill->text}}</p>
                                @if(isset($bill->file))
                                    @foreach($bill->file->src as $file)
                                        <embed src="/{{$file}}" class="mt-1 d-none  files{{$bill->id}}" type="application/pdf" height="400px" width="100%">
                                    @endforeach
                                    <button class="btn btn-primary mt-2 shadow btnShowFile" onclick="showOrHideFile(this.id)" id="{{$bill->id}}" data-file="{{$bill->id}}">Файл</button>
                                @endif
                                
                                @if($bill->status == 2)
                                    <div class="row">
                                        <div class="col-12 mt-2 text-center">
                                            <hr>
                                            <div class="h4" role="group" aria-label="Basic example">
                                                Отказано
                                            </div>
                                        </div>
                                    </div>
                                @elseif($bill->status == 1 and $bill->bill_type_id == 4)
                                    <div class="row">
                                        <div class="col-12 mt-2 text-center">
                                            <hr>
                                            <div class="h4" role="group" aria-label="Basic example">
                                                Оплачено
                                            </div>
                                        </div>
                                    </div>
                                @elseif($bill->bill_type_id == $user->user_role_id and !in_array($bill->bill_type_id,[3,4]))
                                    <div class="row">
                                        <div class="btn-group col-12" role="group" aria-label="Basic example">
                                            <a href="{{route('bill.consult',['bill' => $bill,'type' => 'accept'])}}" class="btn btn-block btn-success my-1 text-light">Утвердить</a>
                                            <a href="{{route('bill.consult',['bill' => $bill,'type' => 'decline'])}}" class="btn btn-block btn-danger my-1 text-light">Отказать</a>
                                        </div>
                                    </div>
                                @elseif($bill->bill_type_id == $user->user_role_id and $bill->bill_type_id == 4)
                                    <div class="row">
                                        <div class="btn-group col-12" role="group" aria-label="Basic example">
                                            <a href="{{route('bill.consult',['bill' => $bill,'type' => 'accept'])}}" class="btn btn-block btn-warning text-light my-1">Оплатить</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
		function showOrHideFile(id) {
			elem = document.getElementById(id);
			file_class = elem.dataset.file;
			files = document.getElementsByClassName('files' + file_class);

			for (let i = 0; i < files.length; i++)
				if (files[i].classList.contains('d-none'))
					files[i].classList.remove('d-none');
				else
					files[i].classList.add('d-none');
		}
    </script>
@endsection
