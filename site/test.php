<?php
include('server/scripts/api.php');

$credits = [
    "login" => "Admin*",
    "passwd" => "qsdfghjklm"
];

echo "</br>";

echo json_encode($credits);

echo "</br>";

$reponse = callAuthApi("/auth/token?", $credits, null, "POST");

var_dump($reponse);

$token = $reponse["response"]["access_token"];

var_dump($token);

$response = callAuthApi("/auth/test", null, $token, "GET");

var_dump($response);
