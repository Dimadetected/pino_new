<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @if(isset($bill->file))
        @foreach($bill->file->src as $file)
            @if(in_array(explode('.',$file)[1],['pdf','doc','docx','excel','xls']))
                <embed src="/{{$file}}" class="mt-1   files{{$bill->id}}" type="application/pdf" height="500px" width="100%">
            @else
                <img src="/{{$file}}" alt="" class="mt-1   files{{$bill->id}}" style="width: 100%;">
        @endif
    @endforeach
@endif
<!-- Scripts -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.js" defer></script>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body class="font-sans antialiased">
<div style="min-height: 90vh">
    
    
    <!-- Page Content -->
    <main class="py-3">
        @php($bill_user_status = $bill->mainUserAccept())
        @if($bill_user_status and isset($bill_user_status->name))
            <div id="app" class="text-right">
                <b class="btn btn-outline-primary p-3" style="font-size: 30px">Утверждено <br> {{$bill_user_status->name}}</b>
            </div>
        @endif
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>
	window.print();
</script>
</body>
</html>

