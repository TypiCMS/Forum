<?php

return [
    'per_page' => 50,
    'order' => [
        'position' => 'asc',
    ],
    'sidebar' => [
        'icon' => '<i class="bi bi-list-ul"></i>',
        'weight' => 7,
    ],
    'permissions' => [
        'read forum_categories' => 'Read',
        'create forum_categories' => 'Create',
        'update forum_categories' => 'Update',
        'delete forum_categories' => 'Delete',
    ],
];
