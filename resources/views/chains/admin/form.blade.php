@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <chains-form :chain="{{$id}}"></chains-form>
        </div>
    </div>
@endsection
