<?php

namespace App\Services;

use League\CommonMark\GithubFlavoredMarkdownConverter;

class Markdown
{
    public static function parse(?string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }

        $converter = new GithubFlavoredMarkdownConverter([
            'allow_unsafe_links' => true,
        ]);

        return $converter->convertToHtml($markdown);
    }
}
