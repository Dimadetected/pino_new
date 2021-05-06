@extends('layouts.app')



@section('content')
    <div class="col-12">
        <div class="row ">
            <div class="col-md-5 offset-md-1">
                <div class="card card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 text-center py-3 border-bottom">
                                <h2>Данные:</h2>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-6">
                                Наименование:
                            </div>
                            <div class="col-6">
                                <b>{{$client->name}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                ИНН:
                            </div>
                            <div class="col-6">
                                <b>{{$client->inn}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                Адресс:
                            </div>
                            <div class="col-6">
                                <b>{{$client->address}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                Расчетный счет:
                            </div>
                            <div class="col-6">
                                <b>{{$client->payment_account}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                КПП:
                            </div>
                            <div class="col-6">
                                <b>{{$client->kpp}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                Контактное лицо:
                            </div>
                            <div class="col-6">
                                <b>{{$client->contact_name}}</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                Телефон:
                            </div>
                            <div class="col-6">
                                <b>{{$client->phone}}</b>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-5">
                <div class="card card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 text-center py-3 border-bottom">
                                <h2>Документы:</h2>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Номер</th>
                                        <th>Дата</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(is_array($client->file_id))
                                        @foreach($client->file_id as $clientFile)
                                            <tr>
                                                <td>{{$clientFile['numb'] != ""?$clientFile['numb']:"Нет"}}</td>
                                                <td>{{$clientFile['date'] != ""?\Carbon\Carbon::parse($clientFile['date'])->format('d.m.Y'):"Нет"}}</td>
                                                <td><a target="_blank"
                                                       href="/{{$files[$clientFile['file_id']]->src[0]}}"
                                                       class="btn btn-primary">Файл</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center py-3 border-bottom">
                                <h2>Утвержденные Договоры:</h2>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Номер</th>
                                        <th>Дата</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($client->contracts as $contract)
                                        <tr>
                                            <td>{{$contract->number}}</td>
                                            <td>{{$contract->date != ""?\Carbon\Carbon::parse($contract->date)->format('d.m.Y'):"Нет"}}</td>
                                            <td><a target="_blank" href="/{{$contract->file->src[0]}}"
                                                   class="btn btn-primary">Файл</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
