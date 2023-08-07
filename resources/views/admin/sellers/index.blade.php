@extends('layouts.app')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center px-6">
               <h1 class="font-bold text-2xl">{{ __("Sellers") }}</h1>
            </div>
            <ul role="list">
               @foreach ($sellers as $seller)
               <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                  <div class="sm:col-span-7">
                     <img src="{person.imageUrl}" alt="" />
                     <b class="text-xl">{{ $seller->name }} {{ $seller->surname }}</b></b>
                     <p><b>Marketplace:</b> {{ $seller->marketplace->country }}</p>
                     <p><b>Email:</b> {{ $seller->email }}</p>
                     <p><b>Phone:</b> {{ $seller->phone }}</p>
                  </div>
                  @if (!is_null($seller->deleted_at))
                     <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('admin.seller.unblock') }}" method="post">
                        @csrf
                        <button type="submit" name="id_seller" value="{{ $seller->id_seller }}"
                           class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                           Unblock
                        </button>
                     </form>
                  @else
                     <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('admin.seller.block') }}" method="post">
                        @csrf
                        <button type="submit" name="id_seller" value="{{ $seller->id_seller }}"
                           class="rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                           Block
                        </button>
                     </form>
                  @endif
                  {{-- <form class="sm:col-span-1 justify-self-end self-center" action="@if (is_null($seller->deleted_at)) {{ route('admin.seller.block') }} @else {{ route('admin.seller.unblock') }} @endif" method="post">
                     @csrf
                     <button type="submit" name="id_seller" value="{{ $seller->id_seller }}"
                        class="rounded-md bg-yellow-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                        @if (is_null($seller->deleted_at)) Block @else Unblock @endif
                     </button>
                  </form> --}}
               </li>
               @endforeach
            </ul>
         </div>
      </div>
   </div>
</div>
@endsection
