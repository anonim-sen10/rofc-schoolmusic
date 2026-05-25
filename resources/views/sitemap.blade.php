<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($routes as $route)
    <url>
        <loc>{{ route($route) }}</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $route === 'home' ? '1.0' : '0.8' }}</priority>
    </url>
@endforeach
</urlset>
