<html>
  <head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  	<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    <title>Darts Round-Robin</title>
  </head>
   <body>
   <center>
   <h1>Darts Round-Robin</h1>
   <a href="index.php">WB Home</a><BR>
   <?php include ("admin/admin.php"); ?>
	
<?php
	$result = mysql_query("SELECT count(*) as num_rows from darts_round_robin");
	$count = mysql_fetch_array($result);
	if ($count['num_rows'] == 0 ) {
		
		
	}else{
		//games have been generated.  List them in 2 sections
		//section 1 is the games that still need to be played within
		//a form.  
		//the second list are completed games that have an edit button beside them
		//order both lists by TID1 for best way to show
		
		//scores
		echo '<h2>Standings</h2>';
			//get the TID and shortnames for each team in the draw
			$result44 = mysql_query("SELECT participants.shortname as shortname, teams.TID as TID from participants, teams where participants.PID = teams.PID and teams.EID = 5 order by TID");
			if (mysql_num_rows($result44) > 0){
				echo '<td valign="top">';
				echo '<table id="myTable" class="tablesorter"><th align="center">Team</th><th>W</th><th>L</th>';
				//build the TID and name arrays
				$TIDprev = 99999;
				$teamarr = 0;
				$teamname = "";
				$TIDs = "";
				while ($row = mysql_fetch_array($result44)) {
					if ($row['TID'] == $TIDprev) {
						$teamname[$teamarr] = $teamname[$teamarr]. ' / '.$row['shortname'];
						
					}else{
						$teamarr++;
						
						$teamname[$teamarr] = $row['shortname'];
						$TIDs[$teamarr] = $row['TID'];
					}
					$TIDprev = $row['TID'];
			}//end there are teams

			//we've got all the teams in array, now get the win totals
			$result55 = mysql_query("SELECT TID, (SELECT count(*) from darts_round_robin where TIDW = TID) as W, (SELECT count(*) from darts_round_robin where TIDL = TID) as L from darts_round_robin, teams where (TID1 = TID or TID2 = TID) and (EID=5) group by 1 order by W desc, L asc");
			
			//display the result
			while ($board = mysql_fetch_array($result55)) {
				for ($j = 1; $j <= sizeof($TIDs); $j++) {
					if ($TIDs[$j] == $board['TID']) {
						$thisteam = $teamname[$j];
					}
				}
					echo '<tr><td>'.$thisteam.'</td><td>'.$board['W'].'</td><td>'.$board['L'].'</td></tr>';
			
			}
			echo'</table>'; //end table for standings in the draw column
		}//end draws for standings
	
		
		echo '<h2>Games To Be Played</h2>';
		//get the games for this draw that are not complete (no TIDW > 0)
			$result = mysql_query("SELECT GID, TID1, TID2 from darts_round_robin where WID = 1 and TIDW = 0 order by TID1");
			echo '<table id="myTable" class="tablesorter" border="1"><tr>';
			//if there are games left in this draw
			if (mysql_num_rows($result) > 0 ){
			echo '<td><table id="myTable" class="tablesorter">';
			//retrieve the players for each team
			while ($game = mysql_fetch_array($result)) {
				//team 1
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 5 and TID = $game[TID1]");
				$first = 1;
				while ($nicks1 = mysql_fetch_array($result2)) {
					if ($first == 1) {
						$team1 = $nicks1['shortname'];
						$first = 0;
					}else{
						$team1 = $team1.' / '.$nicks1['shortname'];
						$first = 1;
					}
				}
				
				//team 2
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 5 and TID = $game[TID2]");
				$first = 1;
				while ($nicks2 = mysql_fetch_array($result2)) {
					if ($first == 1) {
						$team2 = $nicks2['shortname'];
						$first = 0;
					}else{
						$team2 = $team2.' / '.$nicks2['shortname'];
						$first = 1;
					}
				}
				if ($game['TID2'] < 0 ) {
					$team2 = '**WILDCARD TEAM**';
				}
				//display the field for entry
				echo '<tr>';
				echo '<td align="right">'.$team1.'</td>';
				echo '<td align="center" width="30"> <b>VS</b> </td>';
				echo '<td>'.$team2.'</td>';
				echo '</tr>';
			}//end while for each game to be played
			echo '</table>';
			}else{
			//no games left to be played
			echo '<td><table id="myTable" class="tablesorter">';
			echo '<tr><td>The Draw is complete</td></tr>';
			echo '</table>';
		}  
		echo '</tr></table>';

}//end draws
echo '<BR><BR>';

		//now get the games that are complete and have the delete img to remove it
		echo '<h2>Results of Games Played</h2>';
		echo '<table id="myTable" class="tablesorter" border = "1"><tr>';
		$result = mysql_query("SELECT GID, TID1, TID2, TIDW, TIDL from darts_round_robin where WID = 1 and TIDW != 0 order by time_added desc");  
			if (mysql_num_rows($result) == 0 ){
				echo '<td>No games have been played for this draw</td>';
			}else{
				echo '<td><table id="myTable" class="tablesorter">';
			while ($game = mysql_fetch_array($result)) {
				//get the player shortnames for team 1
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 5 and TID = $game[TID1]");
				$first = 1;
				while ($nicks1 = mysql_fetch_array($result2)) {
					if ($first == 1) {
						$team1 = $nicks1['shortname'];
						$first = 0;
					}else{
						$team1 = $team1.' / '.$nicks1['shortname'];
						$first = 1;
					}
				}
				
				//team 2
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 5 and TID = $game[TID2]");
				$first = 1;
				while ($nicks2 = mysql_fetch_array($result2)) {
					if ($first == 1) {
						$team2 = $nicks2['shortname'];
						$first = 0;
					}else{
						$team2 = $team2.' / '.$nicks2['shortname'];
						$first = 1;
					}
				}
				
				if ($game['TID2'] < 0 ){
					$team2 = '**WILDCARD TEAM**';
				}
				//now show the game results
			if ($game['TIDW'] == $game['TID1']){
				echo '<tr><td style="font-size:0.8em">'.$team1.'</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team2.'</td>';
			}else{
				echo '<tr><td style="font-size:0.8em">'.$team2.'</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team1.'</td>';
			}
			
				
				echo '</tr>';
			}
				echo '</table></td>';
				
			}//end rows vs no rows
		echo '</tr></table>';
echo '<BR><BR>';
  echo '<a href="index.php">WB Home</a> ';

?>
   </center>
   </body>
 </html>