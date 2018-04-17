<?php
  session_start();
 	$_SESSION['user'] = 'view';
?>
<html>
  <head>
    <meta charset="utf-8">
    <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
	<LINK REL=StyleSheet HREF="libjs/theme.default.css" TYPE="text/css" MEDIA=screen>
    <title>Wonderboy Home</title>
	  <script type="text/javascript" src="libjs/jquery-1.8.3.js"></script>
	  <script type="text/javascript" src="libjs/jquery.tablesorter.mottie.js"></script> 
	  <style type="text/css">@import "libjs/jquery.countdown.css";
	  	
	  	#defaultCountdown { width: 240px; height: 45px; }
	  </style>
	  <script type="text/javascript" src="libjs/jquery.countdown.js"></script> 
	  <script type="text/javascript">
       $(function () {
	        var austDay = new Date();
	        //austDay = new Date(austDay.getFullYear() + 1, 1 - 1, 26);
	        austDay = new Date("February 15, 2019 19:00:00");
	        $('#defaultCountdown').countdown({until: austDay, timezone: -5});
	        $('#year').text(austDay.getFullYear());
       });
    </script>
    <script type='text/javascript' charset='utf-8' src='libjs/popbox.js'></script>
        <link rel='stylesheet' href='libjs/popbox.css' type='text/css'>
    <script type='text/javascript'>
           $(document).ready(function(){
             $('.popbox').popbox();
             $("#theTable").tablesorter( {
			sortInitialOrder: 'desc',
			sortList: [[2,1]],
			textExtraction:  function(node) {
				//this is done to strip out the text from markup to help in sorting
				//requires the mottie js and the theme.default.css to work and look
				//the same as the standard tablesorter jquery
				var numb = jQuery.trim($(node).text());
				return numb;
				
				//return var;
				} 
			} );
           });
        </script>
        <!--favicon-->
        <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
		<link rel="manifest" href="site.webmanifest">
		<link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
  </head>
  <body>
  	<center>
  	<h1>Wonderboy 2019</h1>
  	<table width="900" cellpadding="10">
  		<tr>
  		<td valign="top" width="40%">
  			<h2>News</h2>
  			<table id="myTable" class="tablesorter" width="100%"> <tr><td>
  			<div id="twitter_div"><font size="3"><ul id="twitter_update_list">
			<?php include ("tweets.php"); ?>
			</ul></font></div>
  		</td></tr></table>	
			</td>
		<?php
		//display the timer.  note the expire date is hardcoded for now.  also, the php date is GMT, the JavaScript date on
		//or around line 20 is local time.  Need to calibrate depending on where the location of the server is.
		$now_date = date("Y-m-d-H");
		$today = strtotime($now_date);
		//echo 'today'.$now_date.'<br>';
		$exp_date = "2019-02-15-19";
		// echo 'expire'.$exp_date.'<br>';
		$expire = strtotime($exp_date);
		//echo $now_date.' '.$today;
	 // echo '<br>';
	 // echo $exp_date.' '.$expire;
	  
		//if ($expire > $today ) {
			echo '<br><center>
		<h4>Feb 15th, 2019 - 7:00pm Opening Ceremonies:</h4>
		<div id="defaultCountdown"></div>
		<br><a href="participants.php">List of Attendees</a><br></center>';
		//}
		?>
		
  <!--this cell is for the leaderboard in row one and tweets in row 2-->
  
  <td rowspan = "2" align = "center" valign="top" width="40%">
  		<!--LEADERBOARD-->
  			<?php include ("admin/admin.php"); ?>
				<?php
					//check to see if there are any points or rows in event_participant_points
					//if rows but no points, don't bother and keep historical.  
					$result = mysql_query("SELECT Sum(Points) as Total FROM event_participant_points where WID=1");
					$row_TTL = mysql_fetch_array($result);
					$current = 0;
				if ($row_TTL['Total'] > 0) {
					  $current = 1;
						//order by this years standings
						$result = mysql_query("select shortname, nickname, event_participant_points.PID as PID, Sum(Points) as Total, ( select points from event_participant_points as b where eid = 3 and b.PID = event_participant_points.PID) as poker, (select count(*) from poker_results c where c.PID = event_participant_points.PID) as made_wildcard from event_participant_points, participants where event_participant_points.PID = participants.PID group by PID order by Total DESC, poker DESC, made_wildcard DESC");
											
						$title = "WB 2019 Leaders";
						$link = '<a href="standings.php">'."Wonderboy 2019 Complete Standings</a><BR><BR><a href=".'"historical_standings.php">Wonderboy Historical Standings</a>';
					} else {
						//order by historical standings
						$result = mysql_query("select nickname, yearly_results.PID as PID, Sum(Points) as Total from yearly_results, standings where yearly_results.PID = standings.PID group by PID order by Total DESC");
						$title = "Wonderboy Overall Leaders";
						$link = '<a href="historical_standings.php">Wonderboy Historical Standings</a>';
					}
					$place = 1;
					$disp_place = 0;
					$prev_total = 10000;
					echo '<h2>'.$title.'</h2>';
					if ($current == 1) {
						echo '<table id="theTable" class="tablesorter" width="100%">';
						echo '<thead><tr><th></th><th>Player</th><th align="center">Points</th><th width=40>Pts Avail</th><th width=40>Max Total</th></tr></thead><tbody>';
					} else {
						echo '<table id="theTable" class="tablesorter" width="100%">';
						echo '<tr><td></td><td><b>Player</b></td><td align="center"><b>Points</b></td></tr><tbody>';
					}	
					
					while($row_PID_TTL = mysql_fetch_array($result)){
						if ($place <= 10) {
							//for each PID, we need to get the total points available in two parts.  Need to get TID for euchre, darts and pong 
							//but the TID is the PID for poker in the still_alive table
							$result2 = mysql_query("select EID, TID, points_left from still_alive_event where TID in (select TID from teams where PID = $row_PID_TTL[PID] and EID in (1,2,5)) order by EID");
							$still_alive_team = 0;
							$events = array();
							$pts = array();
							$partners = array();
							$events_index = 0;
							if (mysql_num_rows($result2) > 0){
								
								
								while($rowTID = mysql_fetch_array($result2)) {
									$still_alive_team = $still_alive_team + $rowTID['points_left'];
									$events[$events_index] = $rowTID['EID'];
									$pts[$events_index] = $rowTID['points_left'];
									//get the partner for the event
									$partner_query = mysql_query("select shortname from participants where PID = (select PID from teams where TID = $rowTID[TID] and PID <> $row_PID_TTL[PID] and EID = $rowTID[EID])");
									if (mysql_num_rows($partner_query) > 0) {
										$partner = mysql_fetch_array($partner_query);
										$partners[$events_index] = $partner['shortname'];
									} else {
										$partners[$events_index] = "nobody";
									}
									$events_index++;
								}
							}
							//now get the poker
							$still_alive_pok = 0;
							$result3 = mysql_query("select points_left from still_alive_event where PID = $row_PID_TTL[PID] and EID = 3");
							if (mysql_num_rows($result3) > 0) {
								$rowPOK = mysql_fetch_array($result3);
								$still_alive_pok = $rowPOK['points_left'];
								$events[$events_index] = 3;
								$pts[$events_index] = $still_alive_pok;
								$events_index++;		
							}
							
							//total the points left
							$points_left = $still_alive_team + $still_alive_pok;
							
							//increment the place if the previous total is different
						  if ($row_PID_TTL['Total'] != $prev_total || $disp_place == 1){
							 $disp_place = $place;
							 $prev_total = $row_PID_TTL['Total'];
						  }
						  
							if ($current == 1) {
							    echo '<tr><td>'.$disp_place.'</td><td><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_PID_TTL['nickname'].'</td><td align="center"><b>'.$row_PID_TTL['Total'].'</b></td><td align="center">'."<div class='popbox'><a class='open' href='#'>".$points_left.'</a>'."<div class='collapse'>
            <div class='box'>
              <div class='arrow'></div>
              <div class='arrow-border'></div>";
              echo '<b>'.$row_PID_TTL['shortname'].'</b><BR>Can still win points in:<BR>';
							for ($i = 0; $i < $events_index; $i++) {
             	if ($events[$i] == 2) {
             		//euchre
             		echo 'Euchre: <b>'.$pts[$i].'pts</b> (partner '.$partners[$i].')<BR>';
             	}
             		if ($events[$i] == 3) {
             			//poker
             			echo 'Poker: <b>'.$pts[$i].'pts</b><BR>';
             	}
             		if ($events[$i] == 1) {
             			//Ping Pong
             			echo 'Ping Pong: <b>'.$pts[$i].'pts</b> (partner '.$partners[$i].')<BR>';
             	}
             		if ($events[$i] == 5) {
             			//Darts
             			echo ' Darts: <b>'.$pts[$i].'pts</b> (partner '.$partners[$i].')<BR>';
             	}
            }
              //echo '<a href="#" class="close">'."close</a>
            echo "</div>
          </div>
        </div> ".'</td><td>'.($row_PID_TTL['Total'] + $points_left).'</td></tr>';
							  } else {
							  	echo '<tr><td>'.$disp_place.'</td><td><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_PID_TTL['nickname'].'</td><td align="center"><b>'.$row_PID_TTL['Total'].'</b></td></tr>';
							  }
						}
						$place++;
						
						
					}
					
					echo '</tbody></table><BR>';	
					echo $link;
					echo '<BR><BR><a href="archives/index.php">Archives</a>';
  		?>
  		</td></tr>
  		<tr><td>
  			<?php
  			// current events
  			$result = mysql_query("SELECT shortname, finals_in_progress, robin_in_progress from event where finals_in_progress = 1 or robin_in_progress = 1");
if (mysql_num_rows($result) > 0) {
	echo '<table id="myTable" class="tablesorter" border="0" width="100%"><tr><td align="center"><h2>Events In Progress</h2></td></tr>';
	while ($rows = mysql_fetch_array($result)) {
		//pong
		if ($rows['shortname'] == 'pong'){
			if ($rows['robin_in_progress'] == 1){
				echo '<tr><td><a href="pingpong_round.php">Ping-Pong Round Robin</a></td></tr>';
			}
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="bracket_playoffs.php?shortname=pong">Ping-Pong Playoffs</a></td></tr>';
			}
		}
		//darts
		if ($rows['shortname'] == 'darts'){
			if ($rows['robin_in_progress'] == 1){
				echo '<tr><td><a href="darts_round.php">Darts Round Robin</a></td></tr>';
				echo '<tr><td><a href="darts_wc.php">Darts Wildcard Showdown</a></td></tr>';
			}
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="bracket_playoffs.php?shortname=darts">Darts Playoffs</a></td></tr>';
			}
		}
		//euchre
		if ($rows['shortname'] == 'euchre'){
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="bracket_playoffs.php?shortname=euchre">Euchre Playoffs</a></td></tr>';
			}
		}
		//tug
		if ($rows['shortname'] == 'tug'){
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="tug_playoffs.php">Tug Of War</a></td></tr>';
			}
		}
		//log
		if ($rows['shortname'] == 'saw'){
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="log_playoffs.php">Log Saw</a></td></tr>';
			}
		}
		//croke
		if ($rows['shortname'] == 'croke'){
			if ($rows['finals_in_progress'] == 1){
				echo '<tr><td><a href="bracket_playoffs.php?shortname=croke">Crokinole</a></td></tr>';
			}
		}
	}
	echo '</table>';
}
  			?>
  		</td></tr>
  		<tr>
  			<td width=20% colspan="2" align="center">
  				<BR>
  			<table id="myTable" class="tablesorter" width="100%">
  			<tr><td align="center" colspan="7"><h2>Events</h2></td></tr>	
	  		<tr>
	  			<td><b>Ping Pong</b></td>
	  			<td><b>Darts</b></td>
	  			<td><b>Euchre</b></td>
	  			<td><b>Poker</b></td>
	  			<td><b>Log Saw</b></td>
	  			<td><b>Tug</b></td>
	  			<td><b>Crokinole</b></td>
	  		</tr>
	  		
<?php
				//display if teams have been formed.  Else there's no point and looks bad.
				$result55 = mysql_query("select * from teams");
				if (mysql_num_rows($result55) > 0) {
		  		echo '<tr>
		  			<td><a href="view_teams.php?shortname=pong">Teams</a></td>
		  			<td><a href="view_teams.php?shortname=darts">Teams</a></td>
		  			<td><a href="view_teams.php?shortname=euchre">Teams</a></td>
		  			<td><a href="view_individuals.php?shortname=poker">Tables</a></td>
		  			<td><a href="view_teams.php?shortname=saw">Teams</a></td>
		  			<td><a href="view_individuals.php?shortname=tug">Teams</a></td>
		  			<td><a href="view_teams.php?shortname=croke">Teams</a></td>
		  		</tr>
		  		<tr>
		  			<td><a href="pingpong_round.php">Round-Robin</a></td>
		  			<td><a href="darts_round.php">Round-Robin</a></td>
		  			<td><a href="euchre_round.php">Round-Robin</a></td>
		  			<td>&nbsp</td>
		  			<td>&nbsp</td>
		  			<td>&nbsp</td>
		  			<td>&nbsp</td>
		  		</tr>
		  			<tr>
		  				<td><a href="bracket_playoffs.php?shortname=pong">Playoffs</a></td>
		  				<td><a href="bracket_playoffs.php?shortname=darts">Playoffs</td>
		  			<td><a href="bracket_playoffs.php?shortname=euchre">Playoffs</a></td>
		  			<td><a href="poker_playoffs.php">Playoffs</a></td>
		  			<td><a href="log_playoffs.php">Log-Saw Results</a></td>
		  			<td><a href="tug_playoffs.php">Tug-Of-War Results</a></td>
		  			<td><a href="bracket_playoffs.php?shortname=croke">Playoffs</a></td>
		  			</tr>';
		  		}
?>
  		</table>
  </td></tr>
</table>
<BR>

  </center>
  <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<!--<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/wonderboytwit.json?callback=twitterCallback2&count=5"></script>-->
	<!--<script type="text/javascript" src="https://api.twitter.com/1/statuses/user_timeline.json?screen_name=wonderboytwit&count=2"></script>-->
	<script type="text/javascript" src="https://api.twitter.com/1/statuses/user_timeline.json?screen_name=wonderboytwit&callback=twitterCallback2&count=5"></script>
  </body>
  
</html>