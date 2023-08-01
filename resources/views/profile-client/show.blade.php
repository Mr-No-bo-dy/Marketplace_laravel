@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900 py-4 ...">
            <div class="flex justify-between items-center">
               <h1 class="font-bold text-2xl">{{ $client->name }} {{ $client->surname }}</h1>
               <form class="my-2" action="{{ route('log_out') }}" method="post">
                  @csrf
                  <button class="inline-block rounded-md bg-gray-400 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                     type="submit">Logout</button>
               </form>
            </div>
            <div class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
               <div class="sm:col-span-6">
                  <img src="{person.imageUrl}" alt="" />
                  <p><b>Email:</b> {{ $client->email }}</p>
                  <p><b>Phone:</b> {{ $client->phone }}</p>
               </div>
               <div class="sm:col-span-1 justify-self-end self-center">
                  <a class="inline-block rounded-md bg-yellow-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-400"
                     href="{{ route('client.edit', $client->id_client) }}">Update</a>
               </div>
               <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('client.delete') }}" method="post">
                  @csrf
                  <button type="submit" name="id_client" value="{{ $client->id_client }}"
                     class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                     Delete
                  </button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection