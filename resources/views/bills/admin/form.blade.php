@extends('layouts.app')



@section('content')
    
    <div class="row ">
        <div class="col-md-4 offset-md-4">
            <form action="{{route($routes['store'],$bill)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card card-body">
                    <div class="form-group">
                        <div class="h2">Создание счета:</div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="text">Описание счета:</label>
                        <textarea name="text" id="text" cols="30" rows="5" class="form-control @error('text') is-invalid @enderror"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="exampleFormControlFile1">Выберите файлы</label>
                        <input type="file" name="files[]" class="form-control-file" multiple id="exampleFormControlFile1">
                    </div>
                    
                    <button class="btn btn-success mt-3">Создать</button>
                </div>
            </form>
        </div>
    </div>

@endsection
