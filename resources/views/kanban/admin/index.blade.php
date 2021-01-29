@extends('layouts.app')


@section('content')
<kanban :user_id="{{auth()->user()->id}}"></kanban>

@endsection
@section('script')

@endsection
