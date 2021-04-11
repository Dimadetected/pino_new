@extends('layouts.app')



@section('content')
    <div class="row ">
        <form class="col-12" action="{{route('clients.store',$client)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 offset-md-2">
                    <div class="card card-body">
                        <div class="form-group">
                            <div class="h2">Создание контрагента:</div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <label for="name">Наименование:</label>
                            <input type="text" id="name" name="name" value="{{$client->name}}"
                                   class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="form-group">
                            <label for="inn">ИНН:</label>
                            <input type="text" id="inn" name="inn" value="{{$client->inn}}"
                                   class="form-control @error('inn') is-invalid @enderror">
                        </div>
                        <div class="form-group">
                            <label for="address">Адрес:</label>
                            <input type="text" id="address" name="address" value="{{$client->address}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="payment_account">Расчетный счет:</label>
                            <input type="text" id="payment_account" name="payment_account"
                                   value="{{$client->payment_account}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="kpp">КПП:</label>
                            <input type="text" id="kpp" name="kpp" value="{{$client->kpp}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="contact_name">Контактное лицо:</label>
                            <input type="text" id="contact_name" name="contact_name" value="{{$client->contact_name}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон:</label>
                            <input type="text" id="phone" name="phone" value="{{$client->phone}}" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-block btn-success mt-3">Создать</button>
                </div>
                <div class="col-md-4 card card-body">
                    @foreach($client->file_id as $clientFile)
                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Номер</label>
                                    <input type="text" name="numbers[]" value="{{$clientFile['numb']}}"
                                           class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Дата</label>
                                    <input type="text" name="dates[]" class="form-control date"
                                           value="{{\Carbon\Carbon::parse($clientFile['date'])->format('d.m.Y')}}" placeholder="00.00.0000">
                                </div>
                            </div>
                            <input type="text" hidden value="{{$clientFile['file_id']}}" name="ids[]">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <a href="/{{$files[$clientFile['file_id']]->src[0]}}" target="_blank"
                                       class="btn btn-block btn-primary">Договор</a>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-block btn-danger delete">Удалить</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group mt-3">
                        <div class="row">
                            <div class="col-md-6"><label for="">Номер</label><input type="text" name="numbers[]"
                                                                                    class="form-control"></div>
                            <div class="col-md-6"><label for="">Дата</label><input type="text" name="dates[]"
                                                                                   class="form-control date"
                                                                                   placeholder="00.00.0000"></div>
                        </div>
                        <input type="text" hidden value="0" name="ids[]">
                        <div class="row mt-3">
                            <div class="col-md-6"><label for="exampleFormControlFile1">Договор:</label><input
                                    type="file" name="files[]" class="form-control-file" id="exampleFormControlFile1">
                            </div>
                            <div class="col-md-6"><label for="">&nbsp;</label>
                                <button class="btn btn-block btn-danger delete">Удалить</button>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="blockAdd">
                        Добавить договор
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script>
        $(document).on('click', '.delete', function (e) {
            e.preventDefault()
            $(this).parent().parent().parent().remove()
        })
        $('#blockAdd').click(function (e) {
            e.preventDefault()
            $('#blockAdd').before("<div class=\"form-group mt-3\"><div class=\"row\"><div class=\"col-md-6\"><label for=\"\">Номер</label><input type=\"text\" name=\"numbers[]\" class=\"form-control\"></div><div class=\"col-md-6\"><label for=\"\">Дата</label><input type=\"text\" name=\"dates[]\" class=\"form-control date\" placeholder=\"00.00.0000\"></div></div><input type=\"text\" hidden value=\"0\" name=\"ids[]\"><div class=\"row mt-3\"><div class=\"col-md-6\"><label for=\"exampleFormControlFile1\">Договор:</label><input type=\"file\" name=\"files[]\" class=\"form-control-file\" id=\"exampleFormControlFile1\"></div><div class=\"col-md-6\"><label for=\"\">&nbsp;</label><button class=\"btn btn-block btn-danger delete\">Удалить</button></div></div></div>")
            $('.date').mask('00.00.0000');
        })
            $('.date').mask('00.00.0000');

        function changeCookies() {
            let val = document.getElementById('chain_id').value
            console.log(val)
            document.cookie = 'chain_id=' + val
            // console.log(document.cookie = 'chain_id=' + val)
        }
    </script>
@endsection
