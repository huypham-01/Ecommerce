<?php 
return [
    'module' => [
        [
            'title' => 'User Management',
            'icon'  => 'fa fa-user',
            'name'  => ['user'],
            'subModule' => [
                [
                    'title' => 'User Group Management',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'User Management',
                    'route' => 'user/index',
                ],
                [
                    'title' => 'Permissions',
                    'route' => 'permission/index',
                ],
                
            ] 
        ],
        [
            'title' => 'Post Management',
            'icon'  => 'fa fa-file',
            'name'  => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Group Management',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'Post Management',
                    'route' => 'post/index',
                ]
                
            ] 
        ],
        [
            'title' => 'General Configuration',
            'icon'  => 'fa fa-file',
            'name'  => ['language'],
            'subModule' => [
                [
                    'title' => 'Language Management',
                    'route' => 'language/index',
                ],
                
            ] 
        ],
        
    ],
];