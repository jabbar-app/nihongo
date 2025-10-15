@props(['disabled' => false, 'hasError' => false])

@php
    $baseClasses = 'rounded-md shadow-sm transition-colors';
    $normalClasses = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
    $errorClasses = 'border-red-300 focus:border-red-500 focus:ring-red-500';
    
    $classes = $baseClasses . ' ' . ($hasError ? $errorClasses : $normalClasses);
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
