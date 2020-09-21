<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="Content-Language" content="en">

        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

        <title>Tweet Markdown</title>

        <meta name="description" content="Effortlessly turn a Tweet into beautiful markdown!">

        <meta property="og:site_name" content="Tweet Markdown">
        <meta property="og:locale" content="en">
        <meta property="og:description" content="Effortlessly turn a Tweet into beautiful markdown!">

        <script type='application/ld+json'>
          {
            "@context": "http:\/\/schema.org",
            "@type": "WebSite",
            "@id": "#website",
            "url": "https:\/\/tweetmarkdown.liamhammett.com",
            "name": "Tweet Markdown"
          }
        </script>

        <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
        <livewire:styles />
        {{ $head ?? '' }}
    </head>
    <body class="antialiased font-sans font-normal bg-gray-100">
        {{ $slot }}

        <livewire:scripts />
        {{ $scripts ?? '' }}
    </body>
</html>
