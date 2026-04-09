@props([
    'type' => 'neutral',
])

@php
    $classes = [
        'success' => 'ui-badge ui-badge-success',
        'warning' => 'ui-badge ui-badge-warning',
        'danger' => 'ui-badge ui-badge-danger',
        'info' => 'ui-badge ui-badge-info',
        'neutral' => 'ui-badge ui-badge-neutral',
    ];
    $className = $classes[$type] ?? $classes['neutral'];
@endphp

<span {{ $attributes->merge(['class' => $className]) }}>{{ $slot }}</span>
