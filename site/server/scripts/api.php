<?php

function _curl($url, $type = "GET", $data = null, $token = null)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

    if (!is_null($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    if (!is_null($token)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/x-www-form-urlencoded"
        ]);
    }

    $response = curl_exec($ch);
    $returnValue = ["status" => 200, "response" => $response];
    if (curl_errno($ch)) {
        $returnValue["status"] = 404;
        $returnValue["response"] = 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    return $returnValue;
}
define("DATA_URL", "http://api-data:8081");
define("AUTH_URL", "http://api-data:8080");

function callDataApi($path, $data = null, $token = null)
{
    return _curl(DATA_URL . $path, $data, $token);
}

function callAuthApi($path, $data = null, $token = null)
{
    return _curl(DATA_URL . $path, $data, $token);
}
