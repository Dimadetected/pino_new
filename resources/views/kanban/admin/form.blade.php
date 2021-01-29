@extends('layouts.app')


@section('content')
    <div class="col-md-6 offset-md-3">
        <form method="post" action="{{route('kanban.store',$kanbanTask->id)}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="form-group">
                <label for="text">Описание:</label>
                <textarea class="form-control" name="text" id="text"></textarea>
            </div>
            <div class="form-group">
                <label for="master_id">Исполнитель:</label>
                <select class="form-control" name="master_id" id="master_id">
                    @foreach($masters as $master)
                        <option value="{{$master->id}}">{{$master->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Введите время выполнения:</label>
                <div class="col-12">
                    <div class="row">
                        <input type="text" id="selector" name="date" class="form-control col-6"
                               placeholder="дд.мм.гггг" value="{{now()->format('d.m.Y')}}">
                        <input type="text" id="hours_minutes" name="hours_minutes" class="form-control col-6"
                               placeholder="" value="{{now()->addHours(6)->format('H:i')}}">
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
