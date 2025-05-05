<?php 
return [
    'module' => [
        [
            'title' => 'QL nhóm thành viên',
            'icon'  => 'fa fa-user',
            'name'  => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm thành viên',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'QL Thành viên',
                    'route' => 'user/index',
                ],
                [
                    'title' => 'QL Quyền',
                    'route' => 'permission/index',
                ],
                
            ] 
        ],
        [
            'title' => 'QL Bài viết',
            'icon'  => 'fa fa-file',
            'name'  => ['post'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm bài viết',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'QL Bài viết',
                    'route' => 'post/index',
                ]
                
            ] 
        ],
        [
            'title' => 'QL Sản phẩm',
            'icon'  => 'fa fa-cube',
            'name'  => ['product', 'attribute'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm sản phẩm',
                    'route' => 'product/catalogue/index',
                ],
                [
                    'title' => 'QL sản phẩm',
                    'route' => 'product/index',
                ],
                [
                    'title' => 'QL loại thuộc tính',
                    'route' => 'attribute/catalogue/index',
                ],
                [
                    'title' => 'QL thuộc tính',
                    'route' => 'attribute/index',
                ],
                
            ] 
        ],
        [
            'title' => 'QL đơn hàng',
            'icon'  => 'fa fa-shopping-cart',
            'name'  => ['menu'],
            'subModule' => [
                [
                    'title' => 'QL khuyến mại',
                    'route' => 'product/catalogue/index',
                ],
                [
                    'title' => 'QL nguồn khách',
                    'route' => 'product/catalogue/index',
                ],
                
            ] 
        ],
        [
            'title' => 'QL nhóm khách hàng',
            'icon'  => 'fa fa-user',
            'name'  => ['customer'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm khách hàng',
                    'route' => 'customer/catalogue/index',
                ],
                [
                    'title' => 'QL khách hàng',
                    'route' => 'customer/index',
                ],
            ] 
        ],
        [
            'title' => 'QL Marketing',
            'icon'  => 'fa fa-money',
            'name'  => ['promotion', 'source'],
            'subModule' => [
                [
                    'title' => 'QL khuyến mại',
                    'route' => 'promotion/index',
                ],
                [
                    'title' => 'QL nguồn khách',
                    'route' => 'source/index',
                ],
                
            ] 
        ],
        [
            'title' => 'QL Banner & Slide',
            'icon'  => 'fa fa-image',
            'name'  => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Slide',
                    'route' => 'slide/index',
                ],
                
            ] 
        ],
        [
            'title' => 'QL Menu',
            'icon'  => 'fa fa-bars',
            'name'  => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt menu',
                    'route' => 'menu/index',
                ],
                
            ] 
        ],
        [
            'title' => 'Cấu hình chung',
            'icon'  => 'fa fa-file',
            'name'  => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'QL Ngôn ngữ',
                    'route' => 'language/index',
                ],
                // [
                //     'title' => 'QL Module',
                //     'route' => 'generate/index',
                // ],
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system/index',
                ],
                [
                    'title' => 'Quản lý Widget',
                    'route' => 'widget/index',
                ],


                
            ] 
        ],
        
    ],
];