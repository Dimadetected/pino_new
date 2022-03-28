@extends('layouts.app')



@section('content')
    <div class="row ">
        <div class="col-10 offset-1 col-lg-4 offset-lg-4">
            <form action="{{route($routes['store'],$bill)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-body">
                    <div class="form-group">
                        <div class="h2">Создание @if(request("type") == 1)счета @else заявки @endif:</div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="chain_id">Цепочка:</label>
                        <select name="chain_id" id="chain_id" class="form-control" onchange="changeCookies()">
                            @foreach($chains as $chain)
                                @if(request("type") == $chain->type)
                                    <option @if($bill->chain_id == $chain->id) selected @elseif(old('chain_id') and old('chain_id') == $chain->id) selected
                                            @endif value="{{$chain->id}}">{{$chain->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="number">Номер:</label>
                        <input type="text" name="number" id="number" class="form-control" value="{{old('number')??0}}">
                    </div>
                    <div class="form-group">
                        <label for="date">Дата:</label>
                        <input type="text" name="date" id="date" placeholder="дд.мм.гггг" class="form-control" value="{{old('date')??now()->format("d.m.Y")}}"
                               onkeyup="dateCheck()">
                    </div>
                    <div class="form-group">
                        <label for="client_id">Контрагент:</label>
                        <select name="client_id" id="client_id" class="form-control js-example-basic-single">
                            @foreach($clients as $client)
                                <option @if(old('text') and old('client_id') == $client->id) selected @endif value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sum">Сумма:</label>
                        <input type="text" name="sum" id="sum" value="{{old('sum')??0}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="text">Описание @if(request("type") == 1)счета: @else заявки:@endif</label>
                        <textarea name="text" id="text" cols="30" rows="5"
                                  class="form-control @error('text') is-invalid @enderror">{{old('text')}}</textarea>
                    </div>
                    <div class="form-group mt-3 @error('files') is-invalid @enderror">
                        @error('badVersionFile')
                        <div class="alert alert-danger">
                            Данный файл имеет неподдерживаемую версию.<br> Для того, чтобы файл был успешно обработан вам необходимо загрузить его на
                            <a target="_blank" href="https://online2pdf.com/convert-pdf-to-pdf">сайт</a> и полученный файл загрузить сюда.
                        </div>
                        @enderror
                        @error('files')
                        <div class="alert alert-danger">
                            Необходимо загрузить файл PDF формата
                        </div>
                        @enderror
                        <label for="exampleFormControlFile1">Выберите файлы</label>
                        <input type="file" name="files" class="form-control-file exampleFormControlFile1" multiple
                               id="exampleFormControlFile1">
                    </div>

                    <button class="btn btn-success mt-3">Создать</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        {{--        --}}
        if (!window.Clipboard) {
            console.log(2222)
            var pasteCatcher = document.createElement("div");

            // Firefox вставляет все изображения в элементы с contenteditable
            pasteCatcher.setAttribute("contenteditable", "");

            pasteCatcher.style.display = "none";
            document.body.appendChild(pasteCatcher);

            // элемент должен быть в фокусе
            pasteCatcher.focus();
            document.addEventListener("click", function () {
                pasteCatcher.focus();
            });
        }
        // добавляем обработчик событию
        window.addEventListener("paste", pasteHandler);

        function pasteHandler(e) {
// если поддерживается event.clipboardData (Chrome)
            if (e.clipboardData) {
                // получаем все содержимое буфера
                var items = e.clipboardData.items;
                if (items) {
                    // находим изображение
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf("image") !== -1) {
                            // представляем изображение в виде файла
                            var blob = items[i].getAsFile();
                            console.log("blob", blob)

                            var fileInput = document.getElementById("exampleFormControlFile1")
                            // fileInput.files = blob
                            let file = new File([blob], "img.jpg", {type: "image/jpeg", lastModified: new Date().getTime()});
                            let container = new DataTransfer();

                            container.items.add(file);
                            fileInput.files = container.files;
                        }
                    }
                }
                // для Firefox проверяем элемент с атрибутом contenteditable
            } else {
            }
        }


        {{----}}

        $('#date').mask('00.00.0000');
        // function dateCheck() {
        //     let val = document.getElementById('date').value;
        //     if ((val.length == 2) || (val.length == 5))
        //         document.getElementById('date').value = val + '.'
        //     if (val.length > 10) {
        //         document.getElementById('date').value = val.substring(0, 10)
        //     }
        // }
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });

        function changeCookies() {
            let val = document.getElementById('chain_id').value
            console.log(val)
            document.cookie = 'chain_id=' + val
            // console.log(document.cookie = 'chain_id=' + val)
        }
    </script>
@endsection
