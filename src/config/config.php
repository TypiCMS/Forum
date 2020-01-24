<?php

return [
    'forum' => [
        'soft_deletes' => true,
        'security' => [
            'limit_time_between_posts' => true,
            'time_between_posts' => 1, // In minutes
        ],
        'email' => [
            'enabled' => true,
            'view' => 'forum::email',
        ],
    ],
    'forum_categories' => [
        'per_page' => 50,
        'order' => [
            'position' => 'asc',
        ],
        'sidebar' => [
            'weight' => 7,
        ],
    ],
    'forum_discussions' => [
        'per_page' => 50,
        'order' => [
            'last_reply_at' => 'desc',
        ],
        'sidebar' => [
            'weight' => 8,
        ],
    ],
    'forum_posts' => [
        'per_page' => 50,
        'order' => [
            'created_at' => 'asc',
        ],
        'sidebar' => [
            'weight' => 9,
        ],
    ],
];
