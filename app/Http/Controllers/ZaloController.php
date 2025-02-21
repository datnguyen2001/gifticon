<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ZaloController extends Controller
{
    public function getAccessToken($code)
    {
        $client = new Client();
        $appId = '3122099038102553739';
        $appSecret = '832LN2yxIXxXHBTX1MWj';
        $redirectUri = urlencode("http://your-website.com/callback");

        // Gửi yêu cầu lấy Access Token
        $response = $client->post('https://oauth.zaloapp.com/v3/access_token', [
            'form_params' => [
                'app_id' => $appId,
                'app_secret' => $appSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];

        // Lưu token vào cơ sở dữ liệu hoặc sử dụng ngay
        return $accessToken;
    }

    public function sendMessage(Request $request)
    {
        $accessToken = env('ZALO_OA_ACCESS_TOKEN');
        $userId = 'user_id'; // ID người nhận tin nhắn

        // Nội dung tin nhắn và hình ảnh
        $messageData = [
            'recipient' => [
                'user_id' => $userId,
            ],
            'message' => [
                'text' => 'Đây là tin nhắn từ Zalo Official Account.',
                'attachments' => [
                    [
                        'type' => 'image',
                        'payload' => [
                            'url' => 'https://example.com/your_image.jpg',
                        ]
                    ]
                ]
            ]
        ];

        // Gửi yêu cầu tới API Zalo
        $client = new Client();
        $response = $client->post("https://openapi.zalo.me/v2.0/oa/message", [
            'json' => $messageData,
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody['error'] == 0) {
            return response()->json(['message' => 'Tin nhắn đã được gửi thành công!']);
        } else {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $responseBody['message']]);
        }
    }
}
