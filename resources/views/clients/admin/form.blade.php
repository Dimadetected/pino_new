@extends('layouts.app')



@section('content')
    <div class="row ">
        <div class="col-md-4 offset-md-4">
            <form action="{{route('clients.store',$client)}}" method="post" enctype="multipart/form-data">
                @csrf
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
                    <div class="form-group mt-3">
                        @if(!is_null($client->file_id))
                            <div class="alert alert-danger">
                                Если вы загрузите новый договор, то старый удалится!
                            </div>
                        @endif
                        <label for="exampleFormControlFile1">Договор:</label>
                        <input type="file" name="files[]" class="form-control-file"
                               id="exampleFormControlFile1">
                    </div>

                    <button class="btn btn-success mt-3">Создать</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script>

        function changeCookies() {
            let val = document.getElementById('chain_id').value
            console.log(val)
            document.cookie = 'chain_id=' + val
            // console.log(document.cookie = 'chain_id=' + val)
        }
    </script>
@endsection
