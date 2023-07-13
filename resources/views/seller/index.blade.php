@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center px-6">
               <h1 class="font-bold text-2xl">{{ __("Sellers") }}</h1>
               <div class="my-2">
                  <a class="inline-block rounded-md bg-green-600 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                     href="{{ route('seller.create') }}">Create</a>
               </div>
            </div>

            <ul role="list">
               @foreach ($sellers as $seller)
               <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                  <div class="sm:col-span-6">
                     <img src="{person.imageUrl}" alt="" />
                     <b class="text-xl">{{ $seller->name }} {{ $seller->surname }}</b></b>
                     <p><b>Marketplace:</b> {{ $seller->country }}</p>
                     <p><b>Email:</b> {{ $seller->email }}</p>
                     <p><b>Phone:</b> {{ $seller->phone }}</p>
                  </div>
                  <div class="sm:col-span-1 justify-self-end self-center">
                     <a class="inline-block rounded-md bg-yellow-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-400"
                        href="{{ route('seller.edit', $seller->id_seller) }}">Update</a>
                  </div>
                  <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('seller.delete') }}" method="post">
                     @csrf

                     <button type="submit" name="id_seller" value="{{ $seller->id_seller }}"
                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        Delete
                     </button>
                  </form>
               </li>
               @endforeach
            </ul>
         </div>
      </div>
   </div>
</div>
@endsection
