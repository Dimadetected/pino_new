@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <users-form :user="{{$id}}"></users-form>
        </div>
    </div>
@endsection
