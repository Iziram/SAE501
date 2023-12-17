<?php

function _curl($url, $type = "GET", $data = null)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

    $headers = [
        'Content-Type: application/json',
    ];

    if (!is_null($data)) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, 1);
    }


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


    $response = curl_exec($ch);
    $returnValue = ["status" => curl_getinfo($ch, CURLINFO_HTTP_CODE), "response" => json_decode($response, true)];
    if (curl_errno($ch)) {
        $returnValue["status"] = 404;
        $returnValue["response"] = 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return $returnValue;
}
define("DATA_URL", "http://api-data:8080");
define("AUTH_URL", "http://api-auth:8081");

function callDataApi($path, $data = null, $type = "GET")
{

    return _curl(DATA_URL . $path, $type, $data);
}

function callAuthApi($path, $data = null, $type = "GET")
{
    return _curl(AUTH_URL . $path, $type, $data);
}
