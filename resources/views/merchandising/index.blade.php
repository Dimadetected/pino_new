@extends('layouts.app')
@section('content')
    <div class="col-12">
        <div class="card card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Начало периода:</label>
                        <input type="text" name="date_start" class="date form-control"
                               value="{{\Carbon\Carbon::parse($date_start)->format('d.m.Y')}}">
                    </div>
                    <div class="col-md-3">
                        <label for="">Конец периода:</label>
                        <input type="text" name="date_end" class="date form-control"
                               value="{{\Carbon\Carbon::parse($date_end)->format('d.m.Y')}}">
                    </div>
                    {{--                    <div class="col-md-4 mt-2">--}}
                    {{--                        <label for="">Номер {{$bill_type}}:</label>--}}
                    {{--                        <input type="text" name="bill_number" class="form-control"--}}
                    {{--                               value="{{$billNumber == 0?"":$billNumber}}">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-md-4 mt-2">--}}
                    {{--                        <label for="">Создатель {{$bill_type}}:</label>--}}
                    {{--                        <select name="bill_creator_id" class="form-control js-example-basic-single">--}}
                    {{--                            <option value="">Не указано</option>--}}
                    {{--                            @foreach($billsCreators as $billCreator)--}}
                    {{--                                <option @if($billCreatorID == $billCreator->id) selected @endif value="{{$billCreator->id}}">{{$billCreator->name}}</option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                    <div class="col-md-3">
                        <label for="">Торговый представитель:</label>
                        <select name="user_id" class="form-control js-example-basic-single">
                            <option value="0">Все</option>
                            @foreach($users as $id => $user)
                                <option @if($userID == $id) selected @endif value="{{$id}}">{{$user}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Сеть:</label>
                        <select name="net_id" class="form-control js-example-basic-single">
                            <option value="0">Все</option>
                            @foreach($nets as $id => $net)
                                <option @if($netID == $id) selected @endif value="{{$id}}">{{$net}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mt-3 text-right">
                        <div class="row text-right">
                            <div class="offset-md-6 col-md-6 text-right">
{{--                                @if(auth()->user()->user_role_id == 1)--}}
                                    <a href="{{$merchLink}}" target="_blank" class="btn btn-success">
                                        Скачать xlsx
                                    </a>
{{--                                @endif--}}
                                <button class="btn btn-primary">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-hover table-responsive">
            <thead>
            <tr>
                <th>#</th>
                <th>Сеть</th>
                <th>Адрес</th>
                <th>Дата</th>
                <th>Торговый представитель</th>
                <th>Товар</th>
                <th>Остаток</th>
                <th>Цена</th>
                <th>Дата розлива</th>
                <th>Комментарий</th>
                <th>Полка</th>
                <th>ТСД</th>
                <th>Срок годности</th>
                <th>Цена</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($merchandisings as $merchandising)
                <tr style="white-space: nowrap !important;">
                    <td>
                        @if(auth()->user()->user_role_id == 1 || ($merchandising->user_id == auth()->user()->id && now()->diffInHours(\Carbon\Carbon::parse($merchandising->created_at)) <= 24 ))
                            <a href="{{route($routes["form"],$merchandising->id)}}">{{$merchandising->id}}</a>
                        @else
                            {{$merchandising->id}}
                        @endif
                    </td>
                    <td>{{$merchandising->net->name}}</td>
                    <td>{{$merchandising->address}}</td>
                    <td>{{\Carbon\Carbon::parse($merchandising->date)->format("d.m.Y")}}</td>
                    <td>{{$merchandising->user->name}}</td>
                    <td>{{$merchandising->product->name}}</td>
                    <td>{{$merchandising->balance}}</td>
                    <td>{{$merchandising->price}}</td>
                    <td>{{\Carbon\Carbon::parse($merchandising->bottled_date)->format("d.m.Y")}}</td>
                    <td>{{$merchandising->comment}}</td>
                    <td>
                        <a href="{{$merchandising->photo_shelf}}" data-fancybox data-caption="Просмотр фото">
                            <img src="{{$merchandising->photo_shelf}}" class="img-fluid" alt="">
                        </a>
                    </td>
                    <td>
                        <a href="{{$merchandising->photo_tsd}}" data-fancybox data-caption="Просмотр фото">
                            <img src="{{$merchandising->photo_tsd}}" class="img-fluid" alt="">
                        </a>
                    </td>
                    <td>
                        <a href="{{$merchandising->photo_expiration_date}}" data-fancybox data-caption="Просмотр фото">
                            <img src="{{$merchandising->photo_expiration_date}}" class="img-fluid" alt="">
                        </a>
                    </td>
                    <td>
                        <a href="{{$merchandising->photo_price}}" data-fancybox data-caption="Просмотр фото">
                            <img src="{{$merchandising->photo_price}}" class="img-fluid" alt="">
                        </a>
                    </td>
                    <td>
                        @if(auth()->user()->user_role_id == 1 || ($merchandising->user_id == auth()->user()->id && now()->diffInHours(\Carbon\Carbon::parse($merchandising->created_at)) <= 24 ))
                            <a href="{{route($routes["form"],$merchandising->id)}}" class="btn btn-primary">Редактировать</a>
                        @endif
                        <a href="{{route($routes["delete"],$merchandising->id)}}" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row">

        </div>
    </div>
    <script>
        import '@fancyapps/fancybox/dist/jquery.fancybox.min'
    </script>
@endsection
