<?php
//
// function getPlayerStats	
//
// input:
//   $PID Player ID
//   $season (can be "current", "alltime", or a year e.g. 2018)
//
// output:
//   $response = json encoded string

$rawData = array("status"=>"OK", "PID"=>$PID, "season"=>$season, "function"=>"getPlayerStats");

$response = json_encode($rawData);
?>
