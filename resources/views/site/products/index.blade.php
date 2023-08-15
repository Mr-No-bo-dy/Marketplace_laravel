@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

{{--         for filters--}}
{{--          <form class="sm:col-span-4 justify-self-end self-center" action="{{ route('product.cart') }}" method="post">--}}
{{--              @csrf--}}
{{--              <div class="sm:col-span-2">--}}
{{--                  <label for="category" class="block text-sm font-medium leading-6 text-gray-900">Category</label>--}}
{{--                  <div class="mt-2">--}}
{{--                      <select name="id_category" id="category"--}}
{{--                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">--}}
{{--                          @foreach ($categories as $category)--}}
{{--                              <option value="{{ $category->id_category }}">{{ $category->name }}</option>--}}
{{--                          @endforeach--}}
{{--                      </select>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              <div class="sm:col-span-2">--}}
{{--                  <button type="submit" name="addToCart" value="1"--}}
{{--                          class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">--}}
{{--                      Show--}}
{{--                  </button>--}}
{{--              </div>--}}
{{--          </form>--}}

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
                     <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                     <input type="hidden" name="price" value="{{ $product->price }}">
                     <button type="submit" name="addToCart" value="1"
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
