@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

{{--         Filters--}}
          <form class="mb-4" action="{{ route('product') }}" method="post">
              @csrf
              <button type="submit" name="resetFilters" value="1"
                      class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                  {{ __('products.resetFilters') }}
              </button>
          </form>
          <form class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 items-end justify-self-end self-center" action="{{ route('product') }}" method="post">
              @csrf

              <div class="sm:col-span-1">
                  {{ $producersSelect }}
              </div>
              <div class="sm:col-span-1">
                  {{ $categoriesSelect }}
              </div>
              <div class="sm:col-span-1">
                  {{ $subcategoriesSelect }}
              </div>
              <div class="sm:col-span-1">
                  {{ $sellersSelect }}
              </div>
              <div class="sm:col-span-1">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.name') }}</label>
                  <input type="text" id="name" name="name" value="{{ $filters['name'] }}" class="block mt-1 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
              <div class="sm:col-span-1">
                    <label for="priceMin" class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.minPrice') }}</label>
                  <input type="number" id="priceMin" name="price[min]" value="{{ $filters['price']['min'] }}" class="block mt-1 w-full rounded-md border-0 py-1.5 text-right text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
              <div class="sm:col-span-1">
                    <label for="priceMax" class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.maxPrice') }}</label>
                  <input type="number" id="priceMax" name="price[max]" value="{{ $filters['price']['max'] }}" class="block mt-1 w-full rounded-md border-0 py-1.5 text-right text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
              <div class="sm:col-span-1">
                  <button type="submit" name="filter" value="1"
                          class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                      {{ __('products.show') }}
                  </button>
              </div>
          </form>

{{--              Products--}}
            <ul role="list" class="mt-3">
               @foreach ($products as $product)
               <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                  <div class="sm:col-span-1">
                     <img class="h-24 w-24 object-contain" src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name . '-pic' }}">
                  </div>
                  <div class="sm:col-span-5">
                     <b class="text-xl">{{ $product->name }}</b>
                     <p><b>{{ __('products.category') }}:</b> {{ $product->category->name }}</p>
                     <p><b>{{ __('products.price') }}:</b> {{ $product->priceFormatted }}</p>
                      <p><b>{{ __('products.rating') }}:</b> {{ ($product->avgRating != 0.00) ? $product->avgRating : __('products.noReviews') }}</p>
                  </div>
                  <div class="sm:col-span-1 justify-self-end self-center">
                     <a class="inline-block rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        href="{{ route('product.show', $product->id_product) }}">{{ __('products.view') }}</a>
                  </div>
                   <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('product.cart') }}" method="post">
                     @csrf
                     <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                     <input type="hidden" name="price" value="{{ $product->price }}">
                     <button type="submit" name="addToCart" value="1"
                        class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                         {{ __('products.addToCart') }}
                     </button>
                  </form>
               </li>
               @endforeach
            </ul>
          <div class="mt-3">
            {{ $products->withQueryString()->links('vendor.pagination.tailwind') }}
          </div>
         </div>
      </div>
   </div>
</div>
@endsection
