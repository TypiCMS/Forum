<?php

return [
    'forum' => [
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
    ],
    'forum_categories' => [
        'per_page' => 50,
        'order' => [
            'position' => 'asc',
        ],
        'sidebar' => [
            'icon' => '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-list-ul" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>',
            'weight' => 7,
        ],
        'permissions' => [
            'read forum_categories' => 'Read',
            'create forum_categories' => 'Create',
            'update forum_categories' => 'Update',
            'delete forum_categories' => 'Delete',
        ],
    ],
    'forum_discussions' => [
        'per_page' => 50,
        'order' => [
            'last_reply_at' => 'desc',
        ],
        'sidebar' => [
            'icon' => '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-text" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/><path fill-rule="evenodd" d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/></svg>',
            'weight' => 8,
        ],
        'permissions' => [
            'read forum_discussions' => 'Read',
            'delete forum_discussions' => 'Delete',
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
