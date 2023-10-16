<?php

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

$auth_header = '';

foreach ($headers as $name => $value) {
  if ($name === "authorization") {
    $auth_header = $value;
  }
}

if ($auth_header === '') {
  http_response_code('401');
  exit;
}

$return_all = FALSE;
// some API keys can pull all the quotes. this parameter indicates you want all of them
if (isset($_GET["return_all"])) {
  $return_all = htmlspecialchars($_GET["return_all"]);
} 

// here you'd probably look things up in a database
$allowed_api_keys_raw = file('scoped_allowed_api_keys_hashed.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$allowed_api_keys_read_all = array();

$allowed_api_keys_read_one = array();

// create two different sets of api keys. one is good for when we have $return_all set, the other is good only for single quotes
foreach ($allowed_api_keys_raw as $row) {
  // 44cc9621613fabd3e3d4c41445f475b106f48037c5b12ae1bc92b5581f581e69,a
  $api_key_struct = explode(",", $row);
  if ($api_key_struct[1] == "o") {
    array_push($allowed_api_keys_read_all, $api_key_struct[0]);
  }
  array_push($allowed_api_keys_read_one, $api_key_struct[0]);
}

$auth_header_hashed = hash("sha256", $auth_header);

// set $allowed_api_keys to the list of api keys based on the operation
// TODO

if (!in_array($auth_header_hashed, $allowed_api_keys, true)) {
  http_response_code('401');
  exit;
}

header("content-type: application/json");

echo json_encode($jokes);

?>
