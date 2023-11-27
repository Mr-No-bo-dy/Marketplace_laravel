<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('admin/admins.title') }}</h1>
            @if(Auth::user()->id === 1)
                <a class="inline-block rounded-md bg-green-600 px-6 py-2 text-m font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                   href="{{ route('register') }}">{{ __('admin/admins.new') }}</a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul class="list p-6 text-gray-900">
                    @foreach ($admins as $admin)
                    <li class="group/item grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-4 sm:px-4 lg:px-8 hover:bg-slate-100 @if(!is_null($admin->deleted_at)) bg-red-100 @endif">
                        <div class="sm:col-span-7">
                            <p><b>{{ __('admin/admins.id') }}:</b> {{ $admin->id }}</p>
                            <p><b>{{ __('admin/admins.name') }}:</b> {{ $admin->name }}</p>
                            <p><b>{{ __('admin/admins.surname') }}:</b> {{ $admin->surname }}</p>
                            <p><b>{{ __('admin/admins.phone') }}:</b> {{ $admin->phone }}</p>
                            <p><b>{{ __('admin/admins.email') }}:</b> {{ $admin->email }}</p>
                        </div>
                        @if(Auth::user()->id === 1 && is_null($admin->deleted_at))
                            <form class="sm:col-span-1 justify-self-end self-center"
                                  action="{{ route('admin.admins.delete') }}" method="POST">
                                @method('DELETE')
                                @csrf

                                <input type="hidden" name="id" value="{{ $admin->id }}">
                                <button type="submit" name="deleteUser" value="1"
                                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    {{ __('admin/admins.delete') }}
                                </button>
                            </form>
                        @elseif (Auth::user()->id === 1 && !is_null($admin->deleted_at))
                            <form class="sm:col-span-1 justify-self-end self-center"
                                  action="{{ route('admin.admins.restore') }}" method="POST">
                                @method('PATCH')
                                @csrf

                                <input type="hidden" name="id" value="{{ $admin->id }}">
                                <button type="submit" name="restoreUser" value="1"
                                        class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    {{ __('admin/admins.restore') }}
                                </button>
                            </form>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
