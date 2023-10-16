<?php

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$key = "hello php-folk!!";

$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmdXNpb25hdXRoLmlvIiwiZXhwIjoxNzIzNDE0NzU0LCJhdWQiOiIyMzhkNDc5My03MGRlLTQxODMtOTcwNy00OGVkOGVjZDE5ZDkiLCJzdWIiOiIxOTAxNmI3My0zZmZhLTRiMjYtODBkOC1hYTkyODc3Mzg2NzciLCJuYW1lIjoiRGFuIE1vb3JlIiwicm9sZXMiOlsiUkVUUklFVkVfSk9LRVMiXX0.TxHnvKhSCG6Op4G2ObAxc02McMWAeFfAlkhp9kuyvsQ";

$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

print_r($decoded);

?>
