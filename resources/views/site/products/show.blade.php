@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center px-6">
               <h1 class="font-bold text-2xl">{{ $product->name }}</h1>
               <div class="sm:col-span-1 justify-self-end self-center">
                  <a class="inline-block my-2 rounded-md bg-gray-400 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400"
                     href="{{ route('product') }}">{{ __('products.Back') }}</a>
               </div>
            </div>
            <div class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 ...">
               <div class="sm:col-span-1">
                   @foreach($product->getMedia('products') as $item)
                       <img class="h-24 w-24 object-contain" src="{{ $item->getUrl() }}" alt="{{ $product->name . '-pic' }}">
                   @endforeach
               </div>
               <div class="sm:col-span-6">
                   <p><b>{{ __('products.Category') }}:</b> {{ $product->category->name }}</p>
                   <p><b>{{ __('products.Subcategory') }}:</b> {{ $product->subcategory->name }}</p>
                   <p><b>{{ __('products.Description') }}:</b> {{ $product->description }}</p>
                   <p><b>{{ __('products.Seller') }}:</b> {{ $product->seller->name }} {{ $product->seller->surname }}</p>
                   <p><b>{{ __('products.Price') }}:</b> {{ $product->priceFormatted }}</p>
                   <p><b>{{ __('products.Rating') }}:</b> {{ $product->avgRating }}</p>
               </div>
                <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('product.cart') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <button type="submit" name="addToCart" value="1"
                            class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        {{ __('products.Add_to_Cart') }}
                    </button>
                </form>
            </div>

{{--                    Reviews--}}
                <div class="mt-2">
                    <h2 class="text-xl font-bold leading-6 text-gray-900">{{ __('products.Reviews') }}</h2>
                    <ul role="list" class="divide-y divide-gray-100">
                        @foreach($product->reviews as $review)
                        <li class="flex justify-between gap-x-6 py-5 shadow-sm">
                            <div class="flex min-w-0 gap-x-4">
                                <div class="min-w-0 flex-auto">
                                    <p class="text-base leading-5 text-gray-500">{{ $review->client->name }} {{ $review->client->surname }}
                                        <span class="inline-block ms-4 text-xs font-normal text-gray-500">{{ $review->updated_at ?? '' }}</span></p>
                                    <p class="mt-1 truncate text-lg font-base leading-6 text-gray-900">{{ $review->comment }}</p>
                                    @if(isset($client_id) && $client_id == $review->client->id_client)
                                        <p><a href="#" class="text-yellow-400">{{ __('products.Edit') }}</a></p>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                                <p class="mt-1 text-xs leading-5 text-gray-500">{{ __('products.Rating') }}</p>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <input class="inline-block w-5 h-5" type="radio" name="rating_{{ $review->id_review }}" value="{{ $i }}" {{($review->rating == $i) ? 'checked' : '' }}
                                        disabled>
                                    @endfor
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <form action="{{ route('product.addReview') }}" method="post">
                        @csrf

                        <div class="mt-3">
                            <p>{{ __('products.Rate_product') }}</p>
                            <div>
                                <label class="inline-block w-5 h-5 font-semibold text-center" for="rating_1">1</label>
                                <label class="inline-block w-5 h-5 font-semibold text-center" for="rating_2">2</label>
                                <label class="inline-block w-5 h-5 font-semibold text-center" for="rating_3">3</label>
                                <label class="inline-block w-5 h-5 font-semibold text-center" for="rating_4">4</label>
                                <label class="inline-block w-5 h-5 font-semibold text-center" for="rating_5">5</label>
                            </div>
                            <div>
                                <input class="inline-block w-5 h-5" id="rating_1" type="radio" name="rating" value="1">
                                <input class="inline-block w-5 h-5" id="rating_2" type="radio" name="rating" value="2">
                                <input class="inline-block w-5 h-5" id="rating_3" type="radio" name="rating" value="3">
                                <input class="inline-block w-5 h-5" id="rating_4" type="radio" name="rating" value="4">
                                <input class="inline-block w-5 h-5" id="rating_5" type="radio" name="rating" value="5">
                            </div>
                        </div>
                        <div class="col-span-full">
                            <label for="review" class="mt-2 block text-sm font-medium leading-6 text-gray-900">{{ __('products.Write_review') }}</label>
                            <div class="mt-2">
                                <textarea id="review" name="review" cols="30" rows="3" required style="max-height: 200px;" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                        <button type="submit" name="addReview" value="1"
                                class="rounded-md mt-2 px-3 py-2 text-sm font-semibold text-white bg-blue-600 shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            {{ __('products.Add_Review') }}
                        </button>
                    </form>
                </div>
         </div>
      </div>
   </div>
</div>
@endsection
