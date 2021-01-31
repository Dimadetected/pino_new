@extends('layouts.app')


@section('content')
    <div class="col-md-6 offset-md-3">
        <form method="post" action="{{route('kanban.store',$kanbanTask->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{$kanbanTask->name??''}}">
            </div>
            <div class="form-group">
                <label for="text">Описание:</label>
                <textarea class="form-control" name="text" id="text">{{$kanbanTask->text??''}}</textarea>
            </div>
            <div class="form-group">
                <label for="user_id">Заказчик:</label>
                <select class="form-control" name="user_id" id="user_id">
                    @foreach($masters as $master)
                        <option @if((isset($kanbanTask->user_id) and $kanbanTask->user_id == $master->id) or (!isset($kanbanTask->user_id) and auth()->user()->id == $master->id)) selected @endif value="{{$master->id}}">{{$master->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="master_id">Ответственный:</label>
                <select class="form-control" name="master_id" id="master_id">
                    @foreach($masters as $master)
                        <option @if(isset($kanbanTask->master_id) and $kanbanTask->master_id == $master->id) selected @endif value="{{$master->id}}">{{$master->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="worker_id">Исполнитель:</label>
                <select class="form-control" name="worker_id" id="worker_id">
                    @foreach($masters as $master)
                        <option @if(isset($kanbanTask->worker_id) and $kanbanTask->worker_id == $master->id) selected @endif value="{{$master->id}}">{{$master->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Введите время выполнения:</label>
                <div class="col-12">
                    <div class="row">
                        <input type="text" id="selector" name="date" class="form-control col-6"
                               placeholder="дд.мм.гггг" value="{{isset($kanbanTask->date)?\Carbon\Carbon::parse($kanbanTask->date)->format('d.m.Y'):now()->format('d.m.Y')}}">
                        <input type="text" id="hours_minutes" name="hours_minutes" class="form-control col-6"
                               placeholder="" value="{{isset($kanbanTask->date)?\Carbon\Carbon::parse($kanbanTask->date)->format('H:i'):now()->addHours(6)->format('H:i')}}">
                    </div>
                </div>
            </div>

            <button class="btn btn-success">Сохранить</button>
        </form>
    </div>
@endsection
@section('script')

    <script>
        $('#selector').mask('00/00/0000')
        $('#hours_minutes').mask('00:00')
    </script>
@endsection
