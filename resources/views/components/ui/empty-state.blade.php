@props([
    'title' => 'No Data Yet',
    'description' => 'Data will appear here once activity starts.',
    'icon' => 'inbox',
])

<div class="empty-state" role="status" aria-live="polite">
    <div class="empty-state-icon"><i data-lucide="{{ $icon }}"></i></div>
    <h4>{{ $title }}</h4>
    <p>{{ $description }}</p>
</div>
