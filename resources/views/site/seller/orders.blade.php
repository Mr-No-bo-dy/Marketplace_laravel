@extends('layouts.site')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center px-6">
                        <h1 class="font-bold text-2xl">{{ __("Orders") }}</h1>
                    </div>

                    <ul role="list">
                        @foreach ($orders as $order)
                            <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 ...">
{{--                                <div class="sm:col-span-1">--}}
{{--                                    <img class="h-24 w-24 object-contain" src="{{ $order->getFirstMediaUrl('products') }}" alt="{{ $order->name . '-pic' }}">--}}
{{--                                </div>--}}
                                <div class="sm:col-span-5">
                                    <p><b>Client:</b> {{ $order->client['name'] }}</p>
                                    <p><b>Status:</b> {{ $order->status }}</p>
                                    <p><b>Date:</b> {{ $order->date }}</p>
                                </div>
{{--                                <div class="sm:col-span-1 justify-self-end self-center">--}}
{{--                                    <a class="inline-block rounded-md bg-yellow-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-400"--}}
{{--                                       href="{{ route('product.edit', $order->id_product) }}">Accept</a>--}}
{{--                                </div>--}}
{{--                                <form class="sm:col-span-1 justify-self-end self-center" action="{{ route('order.delete') }}" method="post">--}}
{{--                                    @csrf--}}

{{--                                    <button type="submit" name="id_product" value="{{ $order->id_order }}"--}}
{{--                                            class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">--}}
{{--                                        Refuse--}}
{{--                                    </button>--}}
{{--                                </form>--}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
