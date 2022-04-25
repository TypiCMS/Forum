<?php

return [
    'linkable_to_page' => true,
    'soft_deletes' => true,
    'disk' => 'local',
    'security' => [
        'limit_time_between_posts' => true,
        'time_between_posts' => 1, // In minutes
    ],
    'email' => [
        'enabled' => true,
        'view' => 'forum::email',
    ],
];
