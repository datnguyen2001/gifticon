<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getTokenZalo()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oauth.zaloapp.com/v4/oa/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'refresh_token=_DOVRTsJjIZnsnTZqfIwA_xS4MNQZkD8uwOcNfkHyqxhfGzsYupoOSYQAcM9akzXtwiRSQhwv6JExH5CejcxUeJYHYhZghW3ZOzk1Ew4a2YteNqncfk0TQFKS5lenA5ipevSNfV0ltVnsc9cZi6DIz-AOtg7fPHollHbTixNjMgzodTXczgoJUdIQaMYxAbiz_bAMQknYKwI-69LtCQzNBZFKN7V_gbwlVPZGkFGd6k-vb5qsDs_M9tgLdhbylbBmkWQJhxuy4lcl2TSbRZNMy2B8sljaCvkozWtVudW-7BViL5gfQEkM_AoRa3MXOrHjPPdJkwIXrQ_d7u6dQMYJ-QMJqZ4g8rZuQrRJeQekLJGlb9DgRhbSyxs57Y0myPcukeHKgIalsk4brr2viQHKXHycdtSXB4X&app_id=4159088486737584130&grant_type=refresh_token',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'secret_key: AOo1CORD9C3RLGxHHdWo'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        if (isset($data->refresh_token)) {
            $dataReturn['status'] = true;
            $dataReturn['access_token'] = $data->access_token;
        } else {
            $dataReturn['status'] = false;
        }
        return $dataReturn;
    }

    public function sendZaloOTP($phone, $otp)
    {
        $data = $this->getTokenZalo();
        if ($data['status'] == false) {
            return back()->with(['error' => 'Refresh Token đã hết hạn']);
        }
        $phoneNumber = '84' . substr($phone, 1);
        $trackingId = Str::random(32);
        $curl = curl_init();

        $postData = array(
            "phone" => $phoneNumber,
            "template_id" => "385250",
            "template_data" => array(
                "otp" => $otp
            ),
            "tracking_id" => $trackingId
        );

        $headers = array(
            'access_token: '.$data['access_token'],
            'Content-Type: application/json'
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://business.openapi.zalo.me/message/template',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
