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

header("content-type: application/json");

echo json_encode($jokes);

?>
