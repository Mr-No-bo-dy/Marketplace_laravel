@extends('layouts.site')

@section('content')
<div class="p-6">
    <h1 class="mb-2 text-2xl text-center">{{ __('index.welcome') }}, <b>{{ $seller_name ?? $client_name ?? __('index.guest') }}</b></h1>
    <p class="text-center">This is just a blank page, please ignore it ;-)</p>
</div>
@endsection
