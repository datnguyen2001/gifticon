<?php

namespace App\Http\Controllers;

use App\Models\SettingModel;
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
        $token = SettingModel::first();
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
            CURLOPT_POSTFIELDS => 'refresh_token=' . $token->refresh_token_zalo . '&app_id=4159088486737584130&grant_type=refresh_token',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'secret_key: AOo1CORD9C3RLGxHHdWo'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        if (isset($data->refresh_token)) {
            $token->refresh_token_zalo = $data->refresh_token;
            $token->save();
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
            return false;
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
