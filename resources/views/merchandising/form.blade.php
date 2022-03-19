@extends('layouts.app')
@section('content')
    <div class="col-12 col-md-10 offset-md-1">
        <form action="/merchandisings" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Сеть:</label>
                    </div>
                    <div class="col-8">
                        <select name="net_id" id="" class="form-control">
                            @foreach($nets as $net)
                                <option @if((old("net_id") != null && old("net_id") == $net->id) || ($merchandising->net_id != null && $merchandising->net_id == $net->id)) selected @endif value="{{$net->id}}">{{$net->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Адрес:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="address" class="form-control" value="{{old("address")??($merchandising->address??"")}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Дата:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="date" id="date" class="form-control" value="{{old("date")??($merchandising->date??now()->format('d.m.Y'))}}">
                    </div>
                </div>
            </div>
            <input type="text" value="{{auth()->user()->id}}" name="user_id" hidden>
            @if(isset($merchandising->id))
                <input type="text" value="{{$merchandising->id}}" name="id" hidden>
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Товар:</label>
                    </div>
                    <div class="col-8">
                        <select name="product_id" id="" class="form-control">
                            @foreach($products as $product)
                                <option @if((old("product_id") != null && old("product_id") == $product->id) || ($merchandising->product_id != null && $merchandising->product_id == $product->id)) selected @endif value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Остаток:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="balance" class="form-control" value="{{old("balance")??($merchandising->balance??0)}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Цена:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="price" class="form-control" value="{{old("price")??($merchandising->price??0)}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Дата розлива:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="bottled_date" id="bottled_date" class="form-control" value="{{old("bottled_date")??($merchandising->bottled_date??now()->format('d.m.Y'))}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Комментарий:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" name="comment" class="form-control" value="{{old("comment")??($merchandising->comment??"")}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Фото полки:</label>
                    </div>
                    <div class="col-8">
                        <input type="file" name="photo_shelf" id="photo_shelf" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Фото ТСД:</label>
                    </div>
                    <div class="col-8">
                        <input type="file" name="photo_tsd" id="photo_tsd" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Фото Срок годности:</label>
                    </div>
                    <div class="col-8">
                        <input type="file" name="photo_expiration_date" id="photo_expiration_date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="">Фото Цены:</label>
                    </div>
                    <div class="col-8">
                        <input type="file" name="photo_price" id="photo_price" class="form-control">
                    </div>
                </div>
            </div>

            <button class="btn btn-success">Сохранить</button>
        </form>
    </div>
    <script>




        $(async function () {
            //2. Получить элемент, к которому необходимо добавить маску
            $("#date").mask("00.00.0000");
            $("#bottled_date").mask("00.00.0000");

            var blob = "";
            var fileForm = new DataTransfer();
            var fileInput = document.getElementById("photo_shelf");
            var fileNew;
            @if(isset($merchandising->photo_shelf) and $merchandising->photo_shelf != "")

                blob = await fetch("/{{$merchandising->photo_shelf}}").then(r => r.blob());

                fileForm = new DataTransfer()

                fileInput = document.getElementById("photo_shelf")

                fileNew = new File([blob], "photo_shelf.jpg", {type: "image/jpeg", lastModified: new Date().getTime()});

                fileForm.items.add(fileNew);

                fileInput.files = fileForm.files;

            @endif

            @if(isset($merchandising->photo_tsd) and ($merchandising->photo_tsd != ""))
                blob = await fetch("/{{$merchandising->photo_tsd}}").then(r => r.blob());

                fileForm = new DataTransfer()

                fileInput = document.getElementById("photo_tsd")

                fileNew = new File([blob], "photo_tsd.jpg", {type: "image/jpeg", lastModified: new Date().getTime()});

                fileForm.items.add(fileNew);

                fileInput.files = fileForm.files;

            @endif

            @if(isset($merchandising->photo_expiration_date))

                blob = await fetch("/{{$merchandising->photo_expiration_date}}").then(r => r.blob());

                fileForm = new DataTransfer()

                fileInput = document.getElementById("photo_expiration_date")

                fileNew = new File([blob], "photo_expiration_date.jpg", {type: "image/jpeg", lastModified: new Date().getTime()});

                fileForm.items.add(fileNew);

                fileInput.files = fileForm.files;

            @endif

            @if(isset($merchandising->photo_price))

                blob = await fetch("/{{$merchandising->photo_price}}").then(r => r.blob());

                fileForm = new DataTransfer()

                fileInput = document.getElementById("photo_price")

                fileNew = new File([blob], "photo_price.jpg", {type: "image/jpeg", lastModified: new Date().getTime()});

                fileForm.items.add(fileNew);

                fileInput.files = fileForm.files;

            @endif
        });
    </script>
@endsection
