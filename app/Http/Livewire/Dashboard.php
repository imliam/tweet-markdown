<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class Dashboard extends Component
{
    public $tweetUrl = '';
    public $markdown = '';

    public function updatedTweetUrl()
    {
        if (empty($this->tweetUrl)) {
            $this->reset();
            $this->resetErrorBag();

            return;
        }

        $this->validate([
            'tweetUrl' => ['url', 'regex:/^http(s)?:\/\/(www\.)?twitter\.com\/([a-zA-Z0-9_]+)\/status\/(\d)(\d+)?$/']
        ], [
            'tweetUrl.required' => 'Please supply a link to a Tweet.',
            'tweetUrl.url' => 'The link given must be to a Tweet URL.',
            'tweetUrl.regex' => 'The link given must be to a valid Tweet.',
        ]);

        $tweetId = $this->getTweetIdFromUrl($this->tweetUrl);
        $tweetDetails = $this->getTweetDetails($tweetId);
        $tweet = $tweetDetails['data'];
        $media = $tweetDetails['includes']['media'] ?? [];
        $author = $this->getTweetAuthorDetails($tweet['author_id']);

        $this->markdown = $this->getMarkdown($tweet, $author, $media);
    }

    protected function getMarkdown($tweet, $author, $media): string
    {
        $markdown = $tweet['text'];
        $markdown = $this->getMarkdownWithInlineLinks($markdown, $tweet);
        $markdown = $this->getMarkdownWithMentionLinks($markdown, $tweet);
        $markdown = $this->getMarkdownWithHashtagLinks($markdown, $tweet);

        $name = $author['name'];
        $username = $author['username'];

        $markdown .= $this->getMarkdownForEmbeddedImages($media);

        $createdAt = Carbon::parse($tweet['created_at'])->toFormattedDateString();
        $markdown .= "\n\n— {$name} ([@{$username}](https://twitter.com/{$username})) [{$createdAt}]({$this->tweetUrl})";

        return $this->makeBlockquote($markdown);
    }

    protected function getMarkdownWithInlineLinks($text, $tweet)
    {
        $urls = collect($tweet['entities']['urls'] ?? [])->unique(function($url) {
            return $url['url'];
        });

        foreach ($urls as $url) {
            $text = str_replace($url['url'], '<' . $url['url'] . '>', $text);
        }

        return $text;
    }

    protected function getMarkdownWithHashtagLinks($text, $tweet)
    {
        $hashtags = collect($tweet['entities']['hashtags'] ?? [])->unique(function($hashtag) {
            return $hashtag['tag'];
        });

        foreach ($hashtags as $hashtag) {
            $tag = $hashtag['tag'];
            $text = str_replace("#{$tag}", "[#{$tag}](https://twitter.com/hashtag/{$tag})", $text);
        }

        return $text;
    }

    protected function getMarkdownWithMentionLinks($text, $tweet)
    {
        foreach ($tweet['entities']['mentions'] ?? [] as $mention) {
            $username = $mention['username'];
            $text = str_replace("@{$username}", "[@{$username}](https://twitter.com/{$username})", $text);
        }

        return $text;
    }

    protected function getMarkdownForEmbeddedImages($media): string
    {
        $embeddedImages = $this->getEmbeddedImages($media);

        if (count($embeddedImages) === 0) {
            return '';
        }

        if (count($embeddedImages) === 1) {
            $imageUrl = $embeddedImages[0]['image'] . '?name=thumb';
            $link = $embeddedImages[0]['link'];

            return "\n\n[![]({$imageUrl})]({$link})";
        }

        $html = '';

        foreach ($embeddedImages as $image) {
            $html .= '<td><a href="' . $image['link'] . '"><img src="' . $image['image']. '" /></a></td>';
        }

        return "\n\n<table><tr>{$html}</tr></table>";
    }

    protected function getEmbeddedImages($media)
    {
        $previews = [];

        foreach ($media as $key => $attachment) {
            if ($attachment['type'] !== 'photo') {
                continue;
            }

            $previews[] = [
                'image' => $attachment['url'],
                'link' => rtrim($this->tweetUrl, '/') . '/photo/' . ($key + 1),
            ];
        }

        return $previews;
    }

    protected function makeBlockquote(string $string): string
    {
        return join("\n", array_map(function ($line) use ($string) {
            return "> {$line}";
        }, explode("\n", $string)));
    }

    protected function getTweetIdFromUrl($tweetUrl): ?string
    {
        $regex = '/^http(s)?:\/\/(www\.)?twitter\.com\/([a-zA-Z0-9_]+)\/status\/(?P<id>\d+)?$/';

        $id = $this->getNamedMatch($tweetUrl, $regex, 'id');

        if ($id === null) {
            throw ValidationException::withMessages([
                'tweetUrl' => 'The link given must be a valid Tweet.'
            ]);
        }

        return $id;
    }

    protected function getNamedMatch($haystack, $pattern, $id)
    {
        $matches = [];

        if (preg_match($pattern, $haystack, $matches)) {
            return $matches[$id] ?? null;
        }

        return null;
    }

    public function setRandomTweetUrl()
    {
        $this->tweetUrl = Arr::random([
            'https://twitter.com/dog_rates/status/1307809689799856128',
            'https://twitter.com/dog_rates/status/1287104098903310337',
            'https://twitter.com/dog_rates/status/1304207621302267904',
            'https://twitter.com/dog_rates/status/1301672329798275073',
            'https://twitter.com/dog_rates/status/1299021585752317952',
            'https://twitter.com/dog_rates/status/1296607949008154624',
            'https://twitter.com/dog_rates/status/1292862057667162112',
            'https://twitter.com/dog_rates/status/1291414861151219718',
            'https://twitter.com/dog_rates/status/1288947550595121152',
            'https://twitter.com/dog_rates/status/1287781423135330304',
        ]);

        $this->updatedTweetUrl();
    }

    protected function getTweetDetails($tweetId)
    {
        return Cache::remember("tweet_{$tweetId}", now()->addDay(), function () use ($tweetId) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.twitter.bearer_token'),
            ])->get('https://api.twitter.com/2/tweets/' . $tweetId . '?tweet.fields=attachments,author_id,created_at,entities,id,text&expansions=attachments.media_keys&media.fields=media_key,preview_image_url,type,url');

            if ($response->status() !== 200) {
                throw ValidationException::withMessages(['tweetUrl' => 'We could not get this Tweet']);
            }

            return $response->json();
        });
    }

    protected function getTweetAuthorDetails($userId)
    {
        return Cache::remember("tweet_author_{$userId}", now()->addDay(), function () use ($userId) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.twitter.bearer_token'),
            ])->get('https://api.twitter.com/2/users/' . $userId);

            if ($response->status() !== 200) {
                throw ValidationException::withMessages(['tweetUrl' => 'We could not get the author of this Tweet']);
            }

            return $response->json('data');
        });
    }

    public function render()
    {
        return view('dashboard');
    }
}
