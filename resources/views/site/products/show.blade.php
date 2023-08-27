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
                     href="{{ route('product') }}">Back</a>
               </div>
            </div>
            <div class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 ...">
               <div class="sm:col-span-1">
                   @foreach($product->getMedia('products') as $item)
                       <img class="h-24 w-24 object-contain" src="{{ $item->getUrl() }}" alt="{{ $product->name . '-pic' }}">
                   @endforeach
               </div>
               <div class="sm:col-span-6">
                   <p><b>Category:</b> {{ $product->category->name }}</p>
                   <p><b>Subcategory:</b> {{ $product->subcategory->name }}</p>
                   <p><b>Description:</b> {{ $product->description }}</p>
                   <p><b>Seller:</b> {{ $product->seller->name }} {{ $product->seller->surname }}</p>
                   <p><b>Price:</b> {{ $product->price }}</p>
                   <p><b>Rating:</b> {{ $product->avgRating }}</p>
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
            </div>

{{--                    Reviews--}}
                <div class="reviews">
                    <h2>Reviews</h2>
                    <p></p>
                    @foreach($product->comments as $comment)
                        <div>
                            <p> {{ $comment->client->name }}</p>
                            <p> {{ $comment->rating }}</p>
                            <p> {{ $comment->comment }}</p>
                        </div>
                    @endforeach
                    <form action="{{ route('product.addReview') }}" method="post">
                        @csrf

                        <textarea name="comment" id="" cols="30" rows="10" required></textarea>
                        <label for="rating_1">1</label>
                        <input type="radio" name="rating" value="1" id="rating_1">
                        <label for="rating_2">2</label>
                        <input type="radio" name="rating" value="2" id="rating_2">
                        <label for="rating_3">3</label>
                        <input type="radio" name="rating" value="3" id="rating_3">
                        <label for="rating_4">4</label>
                        <input type="radio" name="rating" value="4" id="rating_4">
                        <label for="rating_5">5</label>
                        <input type="radio" name="rating" value="5" id="rating_5">
                        <input type="hidden" name="id_product" value="{{ $product->id_product }}">
                        <button type="submit" name="addReview" value="1"
                                class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                            Add Review
                        </button>
                    </form>
                </div>
         </div>
      </div>
   </div>
</div>
@endsection
