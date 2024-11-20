<?php

return [
    'admin' => [
        [
            'name' => 'dashboard',
            'title' => 'Dashboard',
            'icon' => 'bi bi-grid',
            'route' => 'admin.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'banner',
            'title' => 'Cài đặt Banner',
            'icon' => 'bi bi-grid',
            'route' => 'admin.banner.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'category',
            'title' => 'Danh mục sản phẩm',
            'icon' => 'bi bi-grid',
            'route' => 'admin.category.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'shop',
            'title' => 'Quản lý shop',
            'icon' => 'bi bi-grid',
            'route' => 'admin.shop.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'footer',
            'title' => 'Quản lý Footer',
            'icon' => 'bi bi-grid',
            'route' => 'admin.footer.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'setting',
            'title' => 'Cài đặt chung',
            'icon' => 'bi bi-grid',
            'route' => 'admin.setting.index',
            'submenu' => [],
            'number' => 1
        ],

],
    'shop' => [
        [
            'name' => 'dashboard',
            'title' => 'Dashboard',
            'icon' => 'bi bi-grid',
            'route' => 'shop.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'profile',
            'title' => 'Thông tin cá nhân',
            'icon' => 'bi bi-grid',
            'route' => 'shop.profile',
            'submenu' => [],
            'number' => 1
        ],

    ]
];
