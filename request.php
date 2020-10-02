<?php

$api_key = '*****';
$api_salt = '*****';
$url = 'https://app.mango-office.ru/vpbx/config/users/request';
$data = array(); // $data оставляем пустой если нужны все сотрудники.
$json = json_encode($data);
$sign = hash('sha256', $api_key . $json . $api_salt);
$postdata = array(
'vpbx_api_key' => $api_key,
'sign' => $sign,
'json' => $json
);
$post = http_build_query($postdata);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
curl_close($ch);
$resp = json_decode($response, true);
