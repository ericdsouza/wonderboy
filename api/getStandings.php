<?php
//
// function getStandings	
//
// input:
//   $season (can be "current", "alltime", or a year (e.g. 2019)
//
// output:
//   $response = json encoded string

$eventPoints1 = array("special"=>"-", "tug"=>"2", "logsaw"=>"-", "crokinole"=>"3","euchre"=>"0","poker"=>"","pingpong"=>"1","darts"=>"");
$player1 = array("PRANK"=>"1", "PID"=>"38", "PNAME"=>"Chuck", "PointsEarned"=>"6","PointsAvailable"=>"15","TotalPossiblePoints"=>"21","EventPoints"=>$eventPoints1);
$eventPoints2 = array("special"=>"-", "tug"=>"0", "logsaw"=>"-", "crokinole"=>"1","euchre"=>"3","poker"=>"","pingpong"=>"0","darts"=>"1");
$player2 = array("PRANK"=>"2", "PID"=>"39", "PNAME"=>"Doaner", "PointsEarned"=>"5","PointsAvailable"=>"10","TotalPossiblePoints"=>"15","EventPoints"=>$eventPoints2);
$eventPoints3 = array("special"=>"-", "tug"=>"0", "logsaw"=>"3", "crokinole"=>"-","euchre"=>"0","poker"=>"","pingpong"=>"","darts"=>"");
$player3 = array("PRANK"=>"3", "PID"=>"40", "PNAME"=>"Rico", "PointsEarned"=>"4","PointsAvailable"=>"16","TotalPossiblePoints"=>"20","EventPoints"=>$eventPoints3);
$players = array($player1, $player2, $player3);
$rawData = array("status"=>"OK", "season"=>$season, "players"=>$players);

$response = json_encode($rawData);
?>
