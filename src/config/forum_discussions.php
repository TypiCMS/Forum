<?php

return [
    'per_page' => 50,
    'order' => [
        'last_reply_at' => 'desc',
    ],
    'sidebar' => [
        'icon' => '<i class="bi bi-chat-text"></i>',
        'weight' => 8,
    ],
    'permissions' => [
        'read forum_discussions' => 'Read',
        'delete forum_discussions' => 'Delete',
    ],
];
