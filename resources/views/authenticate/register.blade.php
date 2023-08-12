@extends('layouts.site')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <h1 class="text-xl">Registration</h1>
            <form action="{{ route('registration') }}" method="post">
               @csrf

               <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-2">
                     <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                     <div class="mt-2">
                        <input type="text" name="email" id="email" autocomplete="given-name" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="tel" class="block text-sm font-medium leading-6 text-gray-900">Phone</label>
                     <div class="mt-2">
                        <input type="text" name="tel" id="tel" autocomplete="given-name" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                     <div class="mt-2">
                        <input type="password" name="password" id="password" required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                     <div class="mt-2">
                        <input type="text" name="name" id="name" autocomplete="given-name"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="surname" class="block text-sm font-medium leading-6 text-gray-900">Surname</label>
                     <div class="mt-2">
                        <input type="text" name="surname" id="surname" autocomplete="given-name"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="marketplace" class="block text-sm font-medium leading-6 text-gray-900">Marketplace</label>
                     <div class="mt-2">
                        <select name="id_marketplace" id="marketplace"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                           @foreach ($marketplaces as $marketplace)
                           <option value="{{ $marketplace->id_marketplace }}">{{ $marketplace->country_code }} {{ $marketplace->country }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               {{-- <div class="role mt-3">
                  <input id="role" class="me-2 rounded-sm" type="checkbox" name="isSeller" value="1" required>
                  <label for="role" class="check">Register as Seller</label>
               </div> --}}
               <button type="submit"
                  class="rounded-md bg-indigo-600 my-3 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  Register
               </button>
               <span class="inline-block my-3">
                  <a class="inline-block rounded-md bg-gray-600 m-3 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                     href="{{ route('index') }}">Cancel</a>
               </span>
               <span class="inline-block my-3 ms-12">
                  <a class="inline-block rounded-md bg-green-600 m-3 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                     href="{{ route('auth') }}">Login</a>
               </span>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection