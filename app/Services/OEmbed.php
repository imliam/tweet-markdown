<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Embed\Adapters\Webpage;
use Embed\Embed;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class OEmbed
{
    public static function get(string $url): Webpage
    {
        return Cache::remember("oembed_{$url}", now()->addDay(), fn() => Embed::create($url, [
            'min_image_width' => 100,
            'min_image_height' => 100,
            'choose_bigger_image' => true,
            'images_blacklist' => 'example.com/*',
            'url_blacklist' => 'example.com/*',
            'follow_canonical' => true,
        ]));
    }
}
