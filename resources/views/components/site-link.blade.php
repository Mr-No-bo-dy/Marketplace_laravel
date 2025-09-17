@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-gray-700 hover:text-gray-300 hover:bg-transparent focus:text-gray-300 focus:bg-transparent transition duration-150 ease-in-out'
    : 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:text-white hover:bg-gray-700 focus:text-white focus:bg-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
