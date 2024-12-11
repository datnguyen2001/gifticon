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
            'name' => 'overall_performance',
            'title' => 'Hiệu suất tổng thể',
            'icon' => 'bi bi-grid',
            'route' => 'admin.overall_performance',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'performance_shop',
            'title' => 'Hiệu suất từng shop',
            'icon' => 'bi bi-grid',
            'route' => 'admin.performance_shop',
            'parameters' => ['id' => 'all'],
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'user_behavior',
            'title' => 'Hành vi người dùng',
            'icon' => 'bi bi-grid',
            'route' => 'admin.user_behavior',
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
            'name' => 'order',
            'title' => 'Quản lý đơn hàng',
            'icon' => 'bi bi-grid',
            'route' => 'admin.order.index',
            'parameters' => ['status' => 'all'],
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
        [
            'name' => 'membership',
            'title' => 'Hạng thành viên',
            'icon' => 'bi bi-grid',
            'route' => 'admin.membership.index',
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
            'name' => 'revenue_orders',
            'title' => 'Doanh thu và đơn hàng',
            'icon' => 'bi bi-grid',
            'route' => 'shop.revenue-orders',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'product_statistics',
            'title' => 'Thống kê sản phẩm',
            'icon' => 'bi bi-grid',
            'route' => 'shop.product-statistics',
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
        [
            'name' => 'product',
            'title' => 'Sản phẩm',
            'icon' => 'bi bi-grid',
            'route' => 'shop.product.index',
            'submenu' => [],
            'number' => 1
        ],
        [
            'name' => 'order',
            'title' => 'Quản lý đơn hàng',
            'icon' => 'bi bi-grid',
            'route' => 'shop.order.index',
            'parameters' => ['status' => 'all'],
            'submenu' => [],
            'number' => 1
        ],
    ]
];
