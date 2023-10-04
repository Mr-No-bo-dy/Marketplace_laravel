@extends('layouts.app')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center px-6">
               <h1 class="font-bold text-2xl">{{ __('admin/clients.title') }}</h1>
            </div>
            <ul role="list">
               @foreach ($clients as $client)
               <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                  <div class="sm:col-span-7">
                     <b class="text-xl">{{ $client->name }} {{ $client->surname }}</b>
                     <p><b>{{ __('admin/clients.email') }}:</b> {{ $client->email }}</p>
                     <p><b>{{ __('admin/clients.phone') }}:</b> {{ $client->phone }}</p>
                     <p><b>{{ __('admin/clients.count') }}:</b> {{ $client->total_count }}</p>
                     <p><b>{{ __('admin/clients.total') }}:</b> {{ $client->total_amount }}</p>
                  </div>
               </li>
               @endforeach
            </ul>
         </div>
      </div>
   </div>
</div>
@endsection
