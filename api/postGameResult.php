<?php
//
// function postGamesResult
//
// input:
//   $EID = Event ID
//   $GID = Game ID
//   $WTID = Winning Team ID
//   $LTID = Losing Team ID
//   $WPTS = Winning points
//   $LPTS = Losing points
//
// output:
//   $response = json encoded string

if ($EID=="pingpong") {
	$rawData = array("status"=>"OK", "EID"=>$EID, "WID"=>"WB2019", "function"=>"postGamesResult");
} else {
	$rawData = array("status"=>"ServiceNotImplemented", "reason"=>"postGameResult is not implemented for " . $EID);
}

$response = json_encode($rawData);

?>
