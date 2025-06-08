@props(['href' => null, 'type' => 'link', 'color' => 'blue'])

@php
    $baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';
    $colorClasses = [
        'blue' => 'bg-blue-500 hover:bg-blue-700 focus:ring-blue-500',
        'red' => 'bg-red-500 hover:bg-red-700 focus:ring-red-500',
        'green' => 'bg-green-500 hover:bg-green-700 focus:ring-green-500',
        'gray' => 'bg-gray-500 hover:bg-gray-700 focus:ring-gray-500',
    ];
@endphp

@if($type === 'link')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses[$color]]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $colorClasses[$color]]) }}>
        {{ $slot }}
    </button>
@endif 