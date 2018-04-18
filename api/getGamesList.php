<?php
//
// function getGamesList
//
// input:
//   $EID = Event ID
//   $gamestatus = string 'scheduled', 'played', 'all'
//
// output:
//   $response = json encoded string

$game1 = array("GID"=>"201886", "TID1"=>"201802", "TID2"=>"201805", "game-type"=>"roundrobin", "game-status"=>"played", "TNAME1"=>"Roach Rico", "TNAME2"=>"Panko Bullet", "T1PTS"=>21, "T2PTS"=>17);
$game2 = array("GID"=>"201887", "TID1"=>"201804", "TID2"=>"201817", "game-type"=>"roundrobin", "game-status"=>"scheduled", "TNAME1"=>"Roach Jr Bridesmaid", "TNAME2"=>"Chuck Wolfman");
$game3 = array("GID"=>"201887", "TID1"=>"TBD", "TID2"=>"TBD", "game-type"=>"playoff", "game-status"=>"scheduled", "TNAME1"=>"Team 2", "TNAME2"=>"Team 5");
$game4 = array("GID"=>"201887", "TID1"=>"TBD", "TID2"=>"TBD", "game-type"=>"playoff", "game-status"=>"scheduled", "TNAME1"=>"Winner of 2vs5", "TNAME2"=>"Winner of 3vs4");
if($gamestatus=="scheduled") {
	$games = array($game2, $game3, $game4);
} else {
	$games = array($game1, $game2, $game3, $game4);
}
$rawData = array("status"=>"OK", "EID"=>$EID, "WID"=>"WB2019", "games"=>$games);

$response = json_encode($rawData);

?>
