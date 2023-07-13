@extends('layouts.app')

@section('content')
<div class="py-6">
   <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
         <div class="p-6 text-gray-900">
            <h1>Create Marketplace</h1>
            <form action="{{ route('admin.marketplace.store') }}" method="post">
               @csrf

               <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-2">
                     <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">Country
                        Code</label>
                     <div class="mt-2">
                        <input type="text" name="country_code" id="first-name" autocomplete="given-name"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="first-name"
                        class="block text-sm font-medium leading-6 text-gray-900">Country</label>
                     <div class="mt-2">
                        <input type="text" name="country" id="first-name" autocomplete="given-name"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
                  <div class="sm:col-span-2">
                     <label for="first-name"
                        class="block text-sm font-medium leading-6 text-gray-900">Currency</label>
                     <div class="mt-2">
                        <input type="text" name="currency" id="first-name" autocomplete="given-name"
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                     </div>
                  </div>
               </div>
               <button type="submit"
                  class="rounded-md bg-indigo-600 my-3 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  Create
               </button>
               <span class="inline-block my-3">
                  <a class="inline-block rounded-md bg-gray-600 m-3 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                     href="{{ route('admin.marketplace') }}">Cancel</a>
               </span>
            </form>

         </div>
      </div>
   </div>
</div>
@endsection
