@extends('layouts.site')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden">
            <div class="mt-4 px-4 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('products.title') }}</h1>
                <form class="text-right" action="{{ route('product') }}" method="POST">
                    @csrf

                    <button type="submit" name="resetFilters" value="1"
                        class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                        {{ __('products.resetFilters') }}
                    </button>
                </form>
            </div>

            <!-- Filters -->
            <div class="p-4 lg:px-8 text-gray-900">
                <form action="{{ route('product') }}" method="POST"
                    class="group/item grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-8 items-end self-center">
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
                        <label for="name"
                            class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.name') }}</label>
                        <input type="search" id="name" name="name" value="{{ $filters['name'] }}"
                            class="block mt-1 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-1">
                        <label for="priceMin"
                            class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.minPrice') }}</label>
                        <input type="number" id="priceMin" name="price[min]" value="{{ $filters['price']['min'] }}"
                            class="block mt-1 w-full rounded-md border-0 py-1.5 text-right text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-1">
                        <label for="priceMax"
                            class="block text-sm font-medium leading-6 text-gray-900">{{ __('products.maxPrice') }}</label>
                        <input type="number" id="priceMax" name="price[max]" value="{{ $filters['price']['max'] }}"
                            class="block mt-1 w-full rounded-md border-0 py-1.5 text-right text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div class="sm:col-span-1 md:justify-self-end">
                        <button type="submit" name="filter" value="1"
                            class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            {{ __('products.show') }}
                        </button>
                    </div>
                </form>

                <!-- Products -->
                <div class="">
                    @if($products->isEmpty())
                        <div class="my-6 flex flex-col justify-center items-center">
                            <svg id="no_results" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" height="100" width="100" fill="#999999">
                                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
                            </svg>
                            <p>{{ __('products.noSuch') }}</p>
                        </div>
                    @else
                    <div class="mx-auto max-w-3xl md:max-w-5xl lg:max-w-7xl py-4 sm:py-6">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 xl:gap-4">
                            @foreach ($products as $product)
                            <div class="p-4 bg-white border-2 border-gray-200 rounded-lg hover:shadow-lg">
                                <a href="{{ route('product.show', $product->id_product) }}" class="group"
                                    title="{{ __('products.view') }}">
                                    <h2 class="text-center text-lg font-bold text-gray-700">{{ $product->name }}</h2>
                                    <div
                                        class="my-2 aspect-h-1 aspect-w-1 h-48 w-full overflow-hidden rounded-lg xl:aspect-h-8 xl:aspect-w-7">
                                        <img src="{{ $product->img_url }}" alt="{{ $product->name . '-pic' }}"
                                            class="h-full w-full object-contain object-center group-hover:opacity-90">
                                    </div>
                                    <div class="px-2">
                                        <p><b>{{ __('products.producer') }}:</b> {{ $product->producer->name }}</p>
                                        <p><b>{{ __('products.rating') }}:</b>
                                            {{ ($product->avgRating != 0.00) ? $product->avgRating : __('products.noReviews') }}
                                        </p>
                                        <p><b>{{ __('products.price') }}:</b> {{ $product->priceFormatted }}</p>
                                    </div>
                                </a>
                                <form class="mt-2 text-right" action="{{ route('cart.add') }}" method="POST">
                                    @csrf

                                    <button type="submit" name="id_product" value="{{ $product->id_product }}"
                                        class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                        {{ __('products.addToCart') }}
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-3">
                    {{ $products->withQueryString()->onEachSide(2)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
