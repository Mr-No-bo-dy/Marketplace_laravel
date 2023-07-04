@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("Marketplaces") }}
                <div class="my-2">
                    <a class="inline-block rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" href="{{ route('admin.marketplace.store') }}">Create</a>
                </div>

                <ul role="list">
                    @foreach ($marketplaces as $marketplace)
                    <li class="group/item hover:bg-slate-100 my-4 ...">
                        <img src="{person.imageUrl}" alt="" />
                        <div>
                            <a href="{person.url}">{{$marketplace->country_code}}</a>
                            <p><b>Country:</b> {{$marketplace->country}}</p>
                            <p><b>Currency:</b> {{$marketplace->currency}}</p>
                        </div>
                        <div class="my-3">
                            <a class="inline-block rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
                                href="{{ route('admin.marketplace.update', $marketplace->id_marketplace) }}">Update</a>
                        </div>
                        <form action="{{ route('admin.marketplace.delete') }}" method="post">
                            @csrf
                            <button type="submit" name="id_marketplace" value="{{$marketplace->id_marketplace}}"
                                class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                Delete
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
