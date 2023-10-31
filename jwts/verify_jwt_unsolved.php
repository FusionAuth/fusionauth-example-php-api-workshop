<?php

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$key = "hello php-folk!!";

// set $jwt to a created JWT so we can verify it
// TODO 

$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

print_r($decoded);

?>
