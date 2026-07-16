@props([
    'name',
    'alt' => '',
    'size' => null,
])

@php
    $style = trim(($size ? "width: {$size}; height: {$size};" : '').' '.$attributes->get('style'));
@endphp

<img
    src="{{ asset('icons/'.$name.'.png') }}"
    alt="{{ $alt }}"
    {{ $attributes->except('style')->merge(['class' => 'app-icon']) }}
    @if($style) style="{{ $style }}" @endif
>
