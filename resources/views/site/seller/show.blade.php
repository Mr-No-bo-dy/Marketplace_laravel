@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center">
               <h1 class="font-bold text-2xl">{{ $seller->name }}</h1>
               <form class="my-2" action="{{ route('log_out') }}" method="post">
                  @csrf

                  <button class="inline-block rounded-md bg-yellow-400 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
                     type="submit">Logout</button>
               </form>
            </div>
            <p><b>ID:</b> {{ $seller->id_seller }}</p>
            <p><b>Email:</b> {{ $seller->email }}</p>
            <p><b>Phone:</b> {{ $seller->phone }}</p>
         </div>
      </div>
   </div>
</div>
@endsection