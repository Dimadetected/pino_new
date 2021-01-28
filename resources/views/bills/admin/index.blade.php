@extends('layouts.app')


@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <div class="container">
        <div class="row ">
            <div class="col-12">
                <div class="card card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Начало периода:</label>
                                <input type="text" name="date_start" class="date form-control"
                                       value="{{\Carbon\Carbon::parse($date_start)->format('d.m.Y')}}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Конец периода:</label>
                                <input type="text" name="date_end" class="date form-control"
                                       value="{{\Carbon\Carbon::parse($date_end)->format('d.m.Y')}}">
                            </div>
                            <div class="col-md-12 mt-3 text-right">
                                <button class="btn btn-primary">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="row">
                    @foreach($bills as $bill)
                        @if(!in_array($bill->chain->organisation_id,$org_ids))
                            @continue
                        @endif
                        <div class="col-md-4 my-2">
                            <div style="position: relative"
                                 class="card card-body shadow mb-5 alert @if($bill->status == 1 and is_null($bill->user_role_id) ) alert-success @elseif($bill->status == 2) alert-danger @endif">
                                <div class=" card-title" style="font-size: 16pt"><a class="text-primary"
                                                                                    href="{{route($routes['view'],$bill)}}">Счет
                                        #{{$bill->id}}</a></div>
                                <div class=" card-title"
                                     style="font-size: 16pt">{{\Carbon\Carbon::parse($bill->created_at)->format('d.m.Y H:i:s')}}</div>
                                @if($bill->status != 2)
                                    <div class="my-2  h4">{{$bill->bill_type->name??$bill->bill_status->name}}
                                    </div>
                                @endif
                                <div style="position:absolute;top: 10px;right: 10px" class="text-right">
                                    <ul style="font-size: 12px">
                                        <li>
                                        {{$bill->user->name}}</li>
                                        @if(isset($bill->date))
                                            <li>Дата: {{\Carbon\Carbon::parse($bill->date)->format('d.m.Y')}}</li>
                                        @endif
                                        @if(isset($bill->number))
                                            <li>№: {{$bill->number}}</li>
                                        @endif
                                        @if(isset($bill->sum))
                                            <li>{{$bill->sum}}р.</li>
                                        @endif
                                    </ul>
                                </div>
                                <hr>
                                <p class="my-3">{{$bill->text}}</p>
                                @if(isset($bill->file))
                                    @foreach($bill->file->src as $file)
                                        @php($explode = explode('.',$file))
                                        @if(in_array(array_pop($explode),['pdf','doc','docx','excel','xls']))
                                            <embed src="/{{$file}}" class="mt-1 d-none  files{{$bill->id}}"
                                                   type="application/pdf" height="400px" width="100%">
                                        @else
                                            <img src="/{{$file}}" alt=""
                                                 class="img-fluid mt-1 d-none  files{{$bill->id}}">
                                        @endif
                                    @endforeach
                                    <button class="btn btn-primary mt-2 shadow btnShowFile"
                                            onclick="showOrHideFile(this.id)" id="{{$bill->id}}"
                                            data-file="{{$bill->id}}">Файл
                                    </button>
                                @endif

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
                                                Завершено
                                            </div>
                                        </div>
                                    </div>
                                @elseif(($bill->user_role_id == $user->user_role_id ) or
                                ($bill->steps == 0 and $bill->user_id == auth()->user()->id) or
                                ($bill->user_role_id == $user->user_role_id and $bill->user_role_id == 4))
                                    <bill-status-change-component :bill="{{$bill}}"></bill-status-change-component>
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

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $.datepicker.regional['ru'] = {
                closeText: 'Закрыть',
                prevText: 'Предыдущий',
                nextText: 'Следующий',
                currentText: 'Сегодня',
                monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
                dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
                dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
                dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                weekHeader: 'Не',
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['ru']);
            $(".date").datepicker();

        });


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
