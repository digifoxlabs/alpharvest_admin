<!DOCTYPE html>
@php
    $siteName = 'Alp Harvest';
    $siteUrl = rtrim(config('app.url', url('/')), '/');
    $currentUrl = url()->current();
    $metaTitle = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $siteName)));
    $metaDescription = trim($__env->yieldContent('meta_description', 'Alp Harvest offers organic ethnic rice, cold-pressed mustard oil, and traditional pickles from Assam, delivered across India.'));
    $metaImage = $__env->yieldContent('meta_image', asset('images/logo.jpeg'));
    $canonicalUrl = $__env->yieldContent('canonical_url', $currentUrl);
    $robotsContent = app()->environment('production')
        ? trim($__env->yieldContent('meta_robots', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'))
        : 'noindex,nofollow,noarchive,nosnippet';
    $inLanguage = str_replace('_', '-', app()->getLocale());
    $structuredData = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => $siteUrl,
            'logo' => asset('images/logo.jpeg'),
            'image' => asset('images/logo.jpeg'),
            'sameAs' => [
                'https://instagram.com/as.alpharvest',
                'https://www.facebook.com/AlpHarvest',
            ],
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer support',
                    'email' => 'as.alpharvest@gmail.com',
                    'telephone' => '+919181081090',
                    'areaServed' => 'IN',
                    'availableLanguage' => ['en-IN', 'en'],
                ],
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $siteUrl,
            'inLanguage' => 'en-IN',
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $metaTitle,
            'url' => $canonicalUrl,
            'description' => $metaDescription,
            'inLanguage' => 'en-IN',
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => $siteUrl,
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $siteName,
            'description' => 'Organic ethnic rice varieties, cold-pressed mustard oil and traditional pickles from Northeast India.',
            'url' => $siteUrl,
            'logo' => asset('images/logo.jpeg'),
            'image' => asset('images/logo.jpeg'),
            'email' => 'as.alpharvest@gmail.com',
            'telephone' => '+919181081090',
            'priceRange' => '₹₹',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Guwahati',
                'addressRegion' => 'Assam',
                'addressCountry' => 'IN',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 26.103678,
                'longitude' => 91.694010,
            ],
            'areaServed' => [
                'Guwahati',
                'Assam',
                'India',
            ],
            'openingHours' => 'Mo-Sa 09:00-18:00',
            'sameAs' => [
                'https://instagram.com/as.alpharvest',
                'https://www.facebook.com/AlpHarvest',
            ],
        ],
    ];
@endphp
<html lang="{{ $inLanguage }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $metaTitle }}</title>
    @vite(['resources/css/website.css','resources/js/website.js'])

    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="language" content="English">
    <meta name="geo.region" content="IN-AS">
    <meta name="geo.placename" content="Guwahati, Assam, India">
    <meta name="geo.position" content="26.103678;91.694010">
    <meta name="ICBM" content="26.103678, 91.694010">
    <meta name="robots" content="{{ $robotsContent }}">
    <meta name="googlebot" content="{{ $robotsContent }}">
    <meta name="theme-color" content="#2d5a27">
    <meta name="format-detection" content="telephone=no">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" href="{{ $canonicalUrl }}" hreflang="en-IN">
    <link rel="alternate" href="{{ $canonicalUrl }}" hreflang="en">
    <link rel="alternate" href="{{ $canonicalUrl }}" hreflang="x-default">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/admin/favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('images/admin/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpeg') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:image:alt" content="{{ $metaTitle }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    <script type="application/ld+json">
        @json($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    </script>
    @yield('structured_data')
</head>

<body>
    <div id="progress-bar"></div>

    @include('partials.website.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.website.footer')
</body>

</html>
