@php
    $menuItems = collect($portal['menu'] ?? [])->map(function (array $item) use ($portal) {
        $isDashboard = ($item['key'] ?? '') === 'dashboard';
        $url = $isDashboard
            ? route(($portal['prefix'] ?? 'portal').'.dashboard')
            : route(($portal['prefix'] ?? 'portal').'.module', ['module' => $item['key']]);

        return [
            'key' => $item['key'] ?? 'module',
            'label' => $item['label'] ?? ucfirst($item['key'] ?? 'Module'),
            'url' => $url,
            'icon' => $item['icon'] ?? 'circle',
        ];
    })->values()->all();

    $panelTitle = $portal['title'] ?? 'SchoolMusic Portal';
    $homeRoute = route(($portal['prefix'] ?? 'portal').'.dashboard');
@endphp

@extends('portal.layouts.app')
