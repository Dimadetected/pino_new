@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <organisations-form :organisation="{{$id}}"></organisations-form>
        </div>
    </div>
@endsection
