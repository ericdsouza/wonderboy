<?php
//
// function getEventTeams
//
// input:
//   $EID = Event ID
//
// output:
//   $response = json encoded string

$rawData = array("status"=>"OK", "EID"=>$EID, "WID"=>"WB2019", "function"=>"getEventTeams");

$response = json_encode($rawData);
?>
