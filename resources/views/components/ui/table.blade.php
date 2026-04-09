@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'table-wrap']) }}>
    <table>
        @if (! empty($headers))
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
