<?php

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;
use \Firebase\JWT\Key;
use \Firebase\JWT\SignatureInvalidException;
use \Firebase\JWT\BeforeValidException;
use \Firebase\JWT\ExpiredException;

$joke_list = array(
  "Chuck Norris doesn't read books. He stares them down until he gets the information he wants.",
  "If you spell Chuck Norris in Scrabble, you win. Forever.",
  "The dinosaurs looked at Chuck Norris the wrong way once. You know what happened to them.",
  "Chuck Norris once roundhouse kicked someone so hard that his foot broke the speed of light",
  "Chuck Norris does not sleep. He waits.",
  "Chuck Norris can dribble a bowling ball.",
  "Chuck Norris once won a game of Connect Four in three moves.",
  "Chuck Norris makes onions cry.",
  "Chuck Norris doesn't wear a watch. He decides what time it is.",
  "Chuck Norris can sneeze with his eyes open."
);

$jokes = array('joke' => $joke_list[array_rand($joke_list)]);

$headers = array_change_key_case(getallheaders(), CASE_LOWER);

$auth_value = '';

foreach ($headers as $name => $value) {
  if ($name === "authorization") {
    $auth_value = $value;
  }
}

$auth_cookie = $_COOKIE['app_at']; // `app.at` is convered to `app_at`

if (isset($auth_cookie)) {
  $auth_value = $auth_cookie;
}

if ($auth_value === '') {
  http_response_code('401');
  exit;
}


$jwt = $auth_value;

$decoded = '';

// ingest the JSON at the JWKS url
// DONE
$jwks_url = 'https://longhornphp.fusionauth.io/.well-known/jwks.json';
$jwks_contents = file_get_contents($jwks_url);
// DONE


// see https://github.com/firebase/php-jwt for exception details
try {
  $jwks = JWK::parseKeySet(json_decode($jwks_contents,true));
  $decoded = JWT::decode($jwt, $jwks);
} catch (InvalidArgumentException $e) {
  http_response_code('401');
  exit;
} catch (DomainException $e) {
  http_response_code('401');
  exit;
} catch (SignatureInvalidException $e) {
  http_response_code('401');
  exit;
} catch (BeforeValidException $e) {
  http_response_code('401');
  exit;
} catch (ExpiredException $e) {
  http_response_code('401');
  exit;
} catch (UnexpectedValueException $e) {
  http_response_code('401');
  exit;
}

// test other claims
if ($decoded->aud !== "e3c3351d-c02a-4db4-926e-748f267baa9d") {
  http_response_code('401');
  exit;
}

if ($decoded->iss !== "https://longhornphp.fusionauth.io") {
  http_response_code('401');
  exit;
}

//examine roles
$roles = $decoded->roles;

$can_get_one_joke = FALSE;
$can_get_all_jokes = FALSE;

$random_joke_role = "RETRIEVE_JOKES";
$all_jokes_role = "RETRIEVE_ALL_JOKES";

if (in_array($random_joke_role, $roles)) {
  $can_get_one_joke = TRUE;
}
if (in_array($all_jokes_role, $roles)) {
  $can_get_one_joke = TRUE;
  $can_get_all_jokes = TRUE;
}

// here's our auth logic

$return_all = FALSE;
// some API keys can pull all the quotes. this parameter indicates you want all of them
if (isset($_GET["return_all"])) {
  $return_all = htmlspecialchars($_GET["return_all"]);
} 

if ($return_all == "true") {
  if ($can_get_all_jokes) {
    // jwt authorized, they requested it
    $jokes = array('jokes' => $joke_list);
  } else {
  // jwt not authorized, they requested it
    http_response_code('401');
    exit;
  }
}

if (!$can_get_one_joke) {
  http_response_code('401');
  exit;
}


header("content-type: application/json");

echo json_encode($jokes);

?>
