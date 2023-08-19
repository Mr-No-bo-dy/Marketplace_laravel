@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <h1 class="m-3 text-2xl">Cart</h1>
            @if (empty($products))
               <p>Your Cart is empty</p>
            @else
               <ul role="list">
                  @foreach ($products as $product)
                  <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 items-center py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                     <div class="sm:col-span-1">
                        <img class="h-24 w-24 object-contain" src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name . '-pic' }}">
                     </div>
                     <div class="sm:col-span-4">
                        <b class="text-xl">{{ $product->name }}</b>
                        <p><b>Price:</b> {{ $product->price }}</p>
                        <p><b>Total:</b> {{ $productData[$product->id_product]['total'] }}</p>
                     </div>
                     <div class="sm:col-span-2 justify-self-end self-center">
                        <form class="inline-block" action="{{ route('cart') }}" method="post">
                           @csrf
                           <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                           <button type="submit" name="plus" value="1"
                              class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                              +
                           </button>
                        </form>
                        <form class="inline-block" action="{{ route('cart') }}" method="post">
                           @csrf
                           <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                            <label for="quantity" class="block mb-1 font-semibold">Quantity</label>
                            <input type="number" name="quantity" value="{{ $productData[$product->id_product]['quantity'] }}" id="quantity" required
                              class="inline-block text-right w-20 rounded-md font-bold border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </form>
                        <form class="inline-block" action="{{ route('cart') }}" method="post">
                           @csrf
                           <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                           <button type="submit" name="minus" value="1"
                              class="rounded-md bg-yellow-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-400">
                              -
                           </button>
                        </form>
                     </div>
                     <form class="sm:col-span-1 pt-7 justify-self-end self-center" action="{{ route('cart') }}" method="post">
                        @csrf
                        <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                        <button type="submit" name="remove" value="1"
                           class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                           Remove
                        </button>
                     </form>
                  </li>
                  @endforeach
               </ul>
                 <div class="flex justify-between py-3 px-7 text-right">
                     <div>
                         <p class="text-lg"><b>Total Quantity:</b> {{ $total['quantity'] }}</p>
                         <p class="text-lg"><b>Total Price:</b> {{ $total['total'] }}</p>
                     </div>
                     <a class="inline-block px-5 py-3 rounded-md text-xl font-semibold text-white bg-blue-600 shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600" href="{{ route('order') }}">Order</a>
                 </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection
