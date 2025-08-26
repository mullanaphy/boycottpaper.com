<?php

namespace App;

class Config
{
    public const MEDIA_DIRECTORY = 'media';

    public const SITE = [
        'baseUrl' => 'https://www.boycottpaper.com',
        'title' => 'Boycott Paper!',
        'description' => 'A webcomic about the perils of paper and the joys of technology.',
        'author' => 'mullanaphy',
        'feedLimit' => 25,
    ];

    public const SOCIAL_MEDIA = [
        ['icon' => 'twitter', 'title' => 'BlueSky', 'url' => 'https://bsky.app/profile/boycottpaper.com'],
        ['icon' => 'facebook', 'title' => 'Facebook', 'url' => 'https://facebook.com/boycottpaper'],
        ['icon' => 'instagram', 'title' => 'Instagram', 'url' => 'https://instagram.com/boycottpaper'],
        ['icon' => 'github', 'title' => 'Github', 'url' => 'https://github.com/mullanaphy/boycottpaper.com'],
        ['icon' => 'rss-fill', 'title' => 'RSS Feed', 'url' => 'https://boycottpaper.com/feed'],
    ];
}
