@extends('layouts.app')



@section('content')
   <div class="col-12">
       <div class="row ">
           <div class="col-md-5 offset-md-1">
               <div class="card card-body">
                   <div class="form-group">
                       <div class="row">
                           <div class="col-12 text-center py-3 border-bottom">
                               <h2>Данные:</h2>
                           </div>
                       </div>
                       <div class="row pt-3">
                           <div class="col-6">
                               Наименование:
                           </div>
                           <div class="col-6">
                               <b>{{$client->name}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               ИНН:
                           </div>
                           <div class="col-6">
                               <b>{{$client->inn}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               Адресс:
                           </div>
                           <div class="col-6">
                               <b>{{$client->address}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               Расчетный счет:
                           </div>
                           <div class="col-6">
                               <b>{{$client->payment_account}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               КПП:
                           </div>
                           <div class="col-6">
                               <b>{{$client->kpp}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               Контактное лицо:
                           </div>
                           <div class="col-6">
                               <b>{{$client->contact_name}}</b>
                           </div>
                       </div>
                   </div>
                   <div class="form-group">
                       <div class="row">
                           <div class="col-6">
                               Телефон:
                           </div>
                           <div class="col-6">
                               <b>{{$client->phone}}</b>
                           </div>
                       </div>
                   </div>

               </div>
           </div>
           <div class="col-md-5">
               <div class="card card-body">
                   <div class="form-group">
                       <div class="row">
                           <div class="col-12 text-center py-3 border-bottom">
                               <h2>Контракт:</h2>
                           </div>
                       </div>
                       <div class="row pt-3">
                           <div class="col-12">

                               @if(isset($client->file))
                                   @foreach($client->file->src as $file)
                                       <a href="/{{$file}}" target="_blank" class="btn btn-primary">Файл</a>

                                       @php($explode = explode('.',$file))
                                       @if(in_array(array_pop($explode),['pdf','doc','docx','excel','xls']))
                                           <embed src="/{{$file}}" class="mt-1   files{{$client->id}}" type="application/pdf"
                                                  height="500px" width="100%">
                                       @else
                                           <img src="/{{$file}}" alt="" class="mt-1   files{{$client->id}}" style="width: 100%;">
                                       @endif

                                   @endforeach
                               @endif
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

@endsection
