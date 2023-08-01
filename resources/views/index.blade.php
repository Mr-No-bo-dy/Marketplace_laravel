@extends('layouts.site')

@section('content')
<div class="p-6">
   <h1>Welcome to our Marketplace</h1>
   @if(isset($seller->name))
      <p><b>Seller:</b> {{ $seller->name }}</p>
      <form class="my-2" action="{{ route('log_out') }}" method="post">
         @csrf
         <button class="inline-block rounded-md bg-gray-400 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
            type="submit">Logout</button>
      </form>
   @elseif (isset($client->name))
      <p><b>Client:</b> {{ $client->name }}</p>
      <form class="my-2" action="{{ route('log_out') }}" method="post">
         @csrf
         <button class="inline-block rounded-md bg-gray-400 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
            type="submit">Logout</button>
      </form>
   @endif
</div>
@endsection