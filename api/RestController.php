<?php
require_once("WonderboyRestHandler.php");

/*
controls the RESTful services
URI mapping
*/

$requestMethod = $_SERVER['REQUEST_METHOD'];

$endpoint = "";
if(isset($_GET["endpoint"]))
	$endpoint = $_GET["endpoint"];

switch($endpoint){
	case "games":
		switch($requestMethod){
			case "GET":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->getGamesList($_GET["EID"],$_GET["gamestatus"]);
				break;

			case "POST":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->postGameResult($_GET["EID"],$_GET["GID"],$_GET["TIDW"],$_GET["TIDL"],$_GET["WPTS"],$_GET["LPTS"]);
				break;

			default:
                                $wonderboyRestHandler = new WonderboyRestHandler();
                                $wonderboyRestHandler->invalidRequest();
				break;
		}
		break;

        case "events":
		switch($requestMethod){
			case "GET":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->getEventTeams($_GET["EID"]);
				break;

			default:
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->invalidRequest();
				break;
		}
                break;

        case "standings":
		switch($requestMethod){
			case "GET":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->getStandings($_GET["season"]);
				break;

			default:
                                $wonderboyRestHandler = new WonderboyRestHandler();
                                $wonderboyRestHandler->invalidRequest();
				break;
		}
                break;

	case "playerstats":
		switch($requestMethod){
			case "GET":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->getPlayerStats($_GET["PID"], $_GET["season"]);
				break;

			default:
                                $wonderboyRestHandler = new WonderboyRestHandler();
                                $wonderboyRestHandler->invalidRequest();
				break;
		}
                break;

	case "playerbio":
		switch($requestMethod){
			case "GET":
				$wonderboyRestHandler = new WonderboyRestHandler();
				$wonderboyRestHandler->getPlayerBio($_GET["PID"]);
				break;

			default:
                                $wonderboyRestHandler = new WonderboyRestHandler();
                                $wonderboyRestHandler->invalidRequest();
				break;
		}
                break;

	default:
                $wonderboyRestHandler = new WonderboyRestHandler();
                $wonderboyRestHandler->invalidRequest();
		break;
}
?>
