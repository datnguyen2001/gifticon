<?php

namespace Database\Seeders;

use App\Models\SettingModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingModel::create([
            'describe' => 'Giftycon Ứng dụng thẻ quà tặng giúp người dùng dễ dàng lựa chọn, gửi và sử dụng thẻ quà tặng cho nhiều mục tiêu như mua sắm, ăn uống, hay làm đẹp. Chỉ cần thao tác trên điện thoại hoặc trang web, bạn có thể nhận được quà nhanh chóng và tiện lợi, phù hợp mọi dịp',
            'phone' => '0123456789',
            'email' => 'ILvietnam@gmail.com',
            'address' => 'V7-B7 The Terra An Hưng La Khe, Hà Đông, Hà Nội',
            'facebook' => 'https://facebook.com/example',
            'zalo' => 'https://zalo.me/example',
            'twitter' => 'https://twitter.com/example',
            'refresh_token_zalo' => 'example_refresh_token'
        ]);
    }
}
