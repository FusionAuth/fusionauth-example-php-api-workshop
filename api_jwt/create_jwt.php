<?php

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require __DIR__ . '/hmac_secret.php';

// User API
$exp = time() + (60*10);
$payload = array(
    "iss" => "fusionauth.io", 
    "exp" => $exp, 
    "aud" => "238d4793-70de-4183-9707-48ed8ecd19d9",
    "sub" => "19016b73-3ffa-4b26-80d8-aa9287738677",
    "name" => "Dan Moore",
    "roles" => ["RETRIEVE_JOKES", "RETRIEVE_ALL_JOKES"]
);

$jwt = JWT::encode($payload, $key, 'HS256');
print($jwt."\n");

?>
