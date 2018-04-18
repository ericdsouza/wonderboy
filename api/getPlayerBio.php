<?php
//
// function getPlayerBio	
//
// input:
//   $PID Player ID
//
// output:
//   $response = json encoded string

$rawData = array("status"=>"OK", "PID"=>$PID, "function"=>"getPlayerBio");

$response = json_encode($rawData);
?>
