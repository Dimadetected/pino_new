@extends('layouts.app')



@section('content')
    <div class="col-12 ">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card card-body">
                    <div class="form-group">
                        <div class="h2">Просмотр договора:</div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="name">Номер:</label>
                        {{$contract->number}}
                    </div>
                    <div class="form-group">
                        <label for="date">Дата:</label>
                        {{\Carbon\Carbon::parse($contract->date)->format('d.m.Y')}}
                    </div>
                    <div class="form-group">
                        <label for="client_id">Контрагент:</label>
                        {{$contract->client->name}}
                    </div>
                    <embed src="/{{$contract->file->src[0]}}" class="mt-1  files1"
                           type="application/pdf" height="400px" width="100%">
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-body">
                    @if(auth()->user()->id == $contract->status)
                        <a href="{{route('contracts.form',['contract' => $contract->id, 'type' => 1])}}" class="btn btn-primary btn-block">
                            Утвердить договор
                        </a>
                    @endif
                    @if((auth()->user()->id == $contract->user_id or in_array(auth()->user()->id,\App\Models\Contract::IDS)) and ($contract->status != 0 or auth()->user()->id = \App\Models\Contract::IDS[2]))
                        <a href="{{route('contracts.form',['contract' => $contract->id, 'type' => 0])}}" class="btn btn-primary btn-block">
                            Редактировать договор
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('#date').mask("00.00.0000")
    </script>
@endsection
