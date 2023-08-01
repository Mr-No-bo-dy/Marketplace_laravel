@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <ul role="list">
               @foreach ($products as $product)
               <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                  <div class="sm:col-span-1">
                     <img class="h-24 w-24 object-contain" src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name . '-pic' }}">
                  </div>
                  <div class="sm:col-span-5">
                     <b class="text-xl">{{ $product->name }}</b>
                     <p><b>Description:</b> {{ $product->description }}</p>
                     <p><b>Price:</b> {{ $product->price }}</p>
                     <p><b>Amount:</b> {{ $product->amount }}</p>
                  </div>
                  <div class="sm:col-span-1 justify-self-end self-center">
                     <a class="inline-block rounded-md bg-blue-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400"
                        href="{{ route('product.show', $product->id_product) }}">View</a>
                  </div>
                  <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('product.cart') }}" method="post">
                     @csrf
                     <button type="submit" name="id_product" value="{{ $product->id_product }}"
                        class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Add to Cart
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
