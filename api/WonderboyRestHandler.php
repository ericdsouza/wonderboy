<?php
require_once("SimpleRest.php");
		
class WonderboyRestHandler extends SimpleRest {

        public function invalidRequest() {

                $statusCode = 405;
                $rawData = array("status"=>"Error");
		$response = json_encode($rawData);

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }

	public function getGamesList($EID, $gamestatus) {

                require_once('getGamesList.php');

		if(empty($response)) {
			$statusCode = 500;
			$rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHTTPHeaders($requestContentType, $statusCode);
		echo $response;
	}

        public function postGameResult($EID, $GID, $TIDW, $TIDL, $WPTS, $LPTS) {

                require_once('postGameResult.php');

                if(empty($response)) {
                        $statusCode = 404;
                        $rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
                } else {
                        $statusCode = 200;
                }

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }

        public function getEventTeams($EID) {

                require_once('getEventTeams.php');

                if(empty($response)) {
                        $statusCode = 404;
                        $rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
                } else {
                        $statusCode = 200;
                }

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }

        public function getStandings($season) {

                require_once('getStandings.php');

                if(empty($response)) {
                        $statusCode = 404;
                        $rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
                } else {
                        $statusCode = 200;
                }

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }

        public function getPlayerStats($PID, $season) {

                require_once('getPlayerStats.php');

                if(empty($response)) {
                        $statusCode = 404;
                        $rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
                } else {
                        $statusCode = 200;
                }

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }

        public function getPlayerBio($PID) {

                require_once('getPlayerBio.php');

                if(empty($response)) {
                        $statusCode = 404;
                        $rawData = array("status"=>"Error", "reason"=>"something went really bad");
			$response = json_encode($rawData);
                } else {
                        $statusCode = 200;
                }

                $requestContentType = $_SERVER['HTTP_ACCEPT'];
                $this ->setHTTPHeaders($requestContentType, $statusCode);
                echo $response;
        }
}
?>
