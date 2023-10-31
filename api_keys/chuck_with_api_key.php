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

// here you'd probably look things up in a database
$allowed_api_keys = file('allowed_api_keys.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// check to see if provided API key is in our datastore
// DONE
if (!in_array($auth_header, $allowed_api_keys, true)) {
  http_response_code('401');
  exit;
}
// DONE

header("content-type: application/json");

echo json_encode($jokes);

?>
