@extends('layouts.site')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="m-3 text-2xl">Cart</h1>
                        <ul role="list">
                            @foreach ($products as $product)
                                <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
                                    <div class="sm:col-span-1">
                                        <img class="h-24 w-24 object-contain" src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name . '-pic' }}">
                                    </div>
                                    <div class="sm:col-span-4">
                                        <b class="text-xl">{{ $product->name }}</b>
                                        <p><b>Price:</b> {{ $product->price }}</p>
                                        <p><b>Total:</b> {{ $productData[$product->id_product]['total'] }}</p>
                                    </div>
                                    <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('cart') }}" method="post">
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
                        <a href="{{ route('make_order') }}">Confirm Order</a>
                </div>
            </div>
        </div>
    </div>
@endsection
