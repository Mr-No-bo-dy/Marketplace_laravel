<x-app-layout>
   <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         {{ __('Users') }}
      </h2>
   </x-slot>

   <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
               @foreach ($users as $user)
                  <p><b>ID:</b> {{ $user->id }}</p>
                  <p><b>Name:</b> {{ $user->name }}</p>
                  <p><b>Email:</b> {{ $user->email }}</p>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</x-app-layout>
