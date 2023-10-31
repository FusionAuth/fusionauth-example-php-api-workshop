<?php

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Firebase\JWT\SignatureInvalidException;
use \Firebase\JWT\BeforeValidException;
use \Firebase\JWT\ExpiredException;

require __DIR__ . '/hmac_secret.php';

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

// see https://github.com/firebase/php-jwt for exception details
try {
  $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
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
if ($decoded->aud !== "238d4793-70de-4183-9707-48ed8ecd19d9") {
  http_response_code('401');
  exit;
}

if ($decoded->iss !== "fusionauth.io") {
  http_response_code('401');
  exit;
}

//examine roles
$roles = $decoded->roles;

$can_get_one_joke = FALSE;
$can_get_all_jokes = FALSE;

// set correct rolenames
// TODO
$random_joke_role = "...";
$all_jokes_role = "...";
// TODO

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
