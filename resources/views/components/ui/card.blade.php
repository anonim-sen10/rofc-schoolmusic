@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'class' => '',
])

<article {{ $attributes->merge(['class' => 'ui-card '.$class]) }}>
    @if ($title || $subtitle || $action)
        <header class="ui-card-header">
            <div>
                @if ($title)
                    <h3 class="ui-card-title">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="ui-card-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($action)
                <div class="ui-card-action">{!! $action !!}</div>
            @endif
        </header>
    @endif

    <div class="ui-card-content">
        {{ $slot }}
    </div>
</article>
