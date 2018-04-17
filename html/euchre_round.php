<html>
  <head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  	<LINK REL=StyleSheet HREF="/WB/style.css" TYPE="text/css" MEDIA=screen>
    <title>Euchre Round-Robin</title>
  </head>
   <body>
   <center>
   <h1>Euchre Round-Robin</h1>
   <?php
		$mode="";
	   if ( $_GET['mode'] == "TV" ){
			//don't show links to home page
			$mode="TV";
		}else{
			echo '<a href="index.php">WB Home</a><BR>';
		}
	?> 
   <?php 
   $path = $_SERVER['DOCUMENT_ROOT'];
   $path .= "/WB/admin/admin.php";
   include ($path);
   //include ("/admin/admin.php"); ?>
	
<?php
	$result = mysql_query("SELECT count(*) as num_rows from euchre_round_robin");
	$count = mysql_fetch_array($result);
	if ($count['num_rows'] == 0 ) {
		
	}else{
		//games have been generated.  List them in 2 sections
		//section 1 is the games that still need to be played within
		//a form.  
		//the second list are completed games that have an edit button beside them
		//order both lists by TID1 for best way to show
		$result = mysql_query("SELECT draws from event where EID = 2");
		$draws = mysql_fetch_array($result);
		
		//scores
		echo '<h2>Standings</h2>';
		echo '<table id="myTable" class="tablesorter" border = "1">';
		echo '<tr>';
		for ($i = 1; $i <= $draws['draws']; $i++) {
			//echo '<td align="center"><b>Draw '.$i.'</b></td>';
		}
		echo '</tr>';
		echo '<tr>';
		for ($i = 1; $i <= $draws['draws']; $i++) {
			//get the TID and shortnames for each team in the draw
			$result44 = mysql_query("SELECT participants.shortname as shortname, teams.TID as TID from participants, teams where participants.PID = teams.PID and teams.EID = 2 and teams.draw = $i order by TID");
			if (mysql_num_rows($result44) > 0){
				echo '<td valign="top">';
				echo '<table id="myTable" class="tablesorter"><th align="center">Team</th><th>GP</th><th>W</th><th>L</th><th>T</th><th>PTS</th><th align = "center">Diff</th>';
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
			}//end there are teams in the draw

			//we've got all the teams in array, now get the win totals
			$result55 = mysql_query("SELECT TID,(SELECT count(*) from euchre_round_robin where TIDW=TID or TIDL=TID or ((TID1=TID or TID2=TID) and tie !=0)) as GP, (SELECT count(*) from euchre_round_robin where TIDW = TID) as W, (SELECT count(*) from euchre_round_robin where TIDL = TID) as L, (SELECT sum(tie) from euchre_round_robin where TID1=TID or TID2=TID) as T, (SELECT SUM(WPTS-LPTS) from euchre_round_robin WHERE TIDW = TID) as PTSFOR, (SELECT SUM(WPTS-LPTS) from euchre_round_robin WHERE TIDL = TID) as PTSAGAINST, (SELECT ifnull(PTSFOR,0) - ifnull(PTSAGAINST,0))  as PDIFF, (((select count(*) from euchre_round_robin where TIDW = TID) * 2) + (select sum(tie) from euchre_round_robin where TID1=TID or TID2=TID)) as PTS from euchre_round_robin, teams where (TID1 = TID or TID2 = TID) and (teams.draw=1 and EID=2) group by 1 order by PTS desc, W desc, PDIFF desc");
			
			//display the result
			$standings = 1;
			while ($board = mysql_fetch_array($result55)) {
				for ($j = 1; $j <= sizeof($TIDs); $j++) {
					if ($TIDs[$j] == $board['TID']) {
						$thisteam = $teamname[$j];
					}
				}
					echo '<tr><td>'.$thisteam.'</td><td>'.$board['GP'].'</td><td>'.$board['W'].'</td><td>'.$board['L'].'</td><td>'.$board['T'].'</td><td><b><font color="black">'.$board['PTS'].'</font></b></td><td align = "center">'.$board['PDIFF'].'</td></tr>';
					if($standings == 6){
						//put a line separator in to separate the top 6
						echo '<tr><td align="center" colspan="7"><img height="40" width="300" src="images/cut_line.png"></td></tr>';
					}
					$standings++;
			
			}
			echo'</table>'; //end table for standings in the draw column
			echo'</td>'; //end the column for the draw
		}//end draws for standings
	}

		echo '</tr></table>';
		
		
		
		//figure out if a smoke break is needed.  get the break interval
		$result = mysql_query("SELECT misc from event where EID = 2");
		$break = false;
		$break_interval = 0;
		while ($itv = mysql_fetch_array($result)) {
			$break_interval = $itv['misc'];
		}
		
		//now get the next round
		$next_round = 0;
		$result = mysql_query("SELECT min(game_num) as mingame from euchre_round_robin where (TIDW=0 and TIDL=0 and tie=0 and table_num > 0)");
		while ($mgame = mysql_fetch_array($result)) {
			$next_round = $mgame['mingame'];
		}

		//now get the max round
		$result = mysql_query("SELECT max(game_num) as maxgame from euchre_round_robin");
		while ($mgame = mysql_fetch_array($result)) {
			$max_round = $mgame['maxgame'];
		}

		echo '<h2>Next Round ('.$next_round.' of '.$max_round.')</h2>';
		
		if (($next_round % $break_interval) == 0){
			//There's a break coming up
			echo'<h3><font color="red">Smoke Break After This Round</font></h3>';
		}
		
		echo '<table id="myTable" class="tablesorter" border = "1">'; //main table.  one column per draw 
		for ($i = 1; $i <= $draws['draws']; $i++) {
			//echo '<th align="center">Draw '.$i.'</th>';
		}
		echo '<tr>';
		for ($i = 1; $i <= $draws['draws']; $i++) {
			
			
			//get the games for this draw that are not complete (no TIDW > 0 or tie = 0)
			$result = mysql_query("SELECT GID, TID1, TID2, table_name as tableName, game_num as gamenum, (select count(*) from euchre_round_robin where game_num = gamenum and TIDW = 0 and tie = 0) as game_per_round, table_num from euchre_round_robin where WID = 1 and TIDW = 0 and tie = 0 order by game_num asc, table_num desc");
			
			//if there are games left in this draw
			if (mysql_num_rows($result) > 0 ){
			echo '<td><table id="myTable" class="tablesorter">';
			//retrieve the players for each team
			$gamenum = 0;
			$gcount = 0;
			$onlyByeLeftInRound = false;
			while ($game = mysql_fetch_array($result)) {
				//team 1
				if($game['TID1'] < 0){
					//-1 TID means no partner i.e. it's a bye 
					$team1 = "";
				}else{
					$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 2 and TID = $game[TID1]");
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
				}
				//team 2
				if($game['TID2'] < 0) {
					$team2 = "";
				}else{
					$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 2 and TID = $game[TID2]");
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
				}
				//display the field for entry
				if ($game['gamenum'] != $gamenum) {
					//since this is the first round and I sort by desc, if the 1st tablenum is -1 then there's only
					//byes left.  We want to suppress showing this and just move onto the next round
					if($game['table_num'] < 0) {
						$onlyByeLeftInRound = true;
					}else{
						$onlyByeLeftInRound = false;
						echo '<tr><td rowspan="'.$game['game_per_round'].'">'.$game['gamenum'].'</td>';
					}
					$gamenum = $game['gamenum'];
					$gcount = 1;
					
				}else{
					echo '<tr>';
					$gcount++;
				}
				if($onlyByeLeftInRound == false){
					if($game['table_num'] < 0){ //this is a bye table so no form, etc.
						echo '<td align="center">BYE</td>';
						if($team1 != "" && $team2 != ""){
							echo '<td colspan="3">'.$team1.' and '.$team2.'</td>';
						}else{
							echo '<td colspan="3">'.$team1.' '.$team2.'</td>';
						}
						echo '</tr>';
					}else{
						echo '<td align="center">'.$game['tableName'].'</td>';
						echo '<td align="right">'.$team1.'</td>';
						echo '<td align="center" width="30"> <b>VS</b> </td>';
						echo '<td>'.$team2.'</td>';
						echo '</tr>';
					}
				if($gcount == $game['game_per_round']){ //buffer the rounds in the table
						echo '<td bgcolor="#FFFFFF" colspan="8">&nbsp;</td>';
						$gcount = 0;
					}				
					
				}
			}//end while for each game to be played
			echo '</table></td>';
			}else{
			//no games left to be played
			echo '<td><table id="myTable" class="tablesorter">';
			echo '<tr><td>The Draw is complete</td></tr>';
			echo '</table></td>';
		}  
	}//games for draw

}//end draws
echo '</tr>';
echo '</table>'; //end main table
echo '<BR><BR>';

		//now get the games that are complete and have the delete img to remove it
		echo '<h2>Results of Games Played</h2>';
		echo '<table id="myTable" class="tablesorter" border = "1"><tr>';
		for ($i = 1; $i <= $draws['draws']; $i++) {
			$result = mysql_query("SELECT GID, TID1, TID2, TIDW, TIDL, WPTS, LPTS, game_num as gamenum, (select count(*) from euchre_round_robin where game_num = gamenum and (TIDW != 0 or tie != 0)) as game_per_round, table_num from euchre_round_robin where WID = 1 and (TIDW != 0 or tie != 0) and table_num > 0 order by game_num asc, table_num desc");  
			if (mysql_num_rows($result) == 0 ){
				echo '<td>No games have been played for this draw</td>';
			}else{
			echo '<td>';
			echo '<table id="myTable" class="tablesorter">';
			$cgamenum = 0;
			$ccount = 0;
			while ($game = mysql_fetch_array($result)) {
				//get the player shortnames for team 1
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 2 and TID = $game[TID1]");
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
				$result2 = mysql_query("SELECT participants.shortname as shortname, teams.PID from teams, participants where participants.PID = teams.PID AND teams.WID = 1 and teams.EID = 2 and TID = $game[TID2]");
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
				
				//now show the game results
			if ($game['TIDW'] == $game['TID1']){
				$pts1 = $game['WPTS'];
				$pts2 = $game['LPTS'];
				$diff1 = $pts1 - $pts2;
				$diff2 = $pts2 - $pts1;
				if ($game['gamenum'] != $cgamenum) {
					echo '<tr><td rowspan="'.$game['game_per_round'].'">'.$game['gamenum'].'</td><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" ><b>'.$pts1.'-'.$pts2.'</b></td>';
					$cgamenum = $game['gamenum'];
					$ccount = 1;
				}else{
					echo '<tr><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" ><b>'.$pts1.'-'.$pts2.'</b></td>';	
					$ccount++;
				}
			}else if($game['TIDW'] == $game['TID2']){
				$pts1 = $game['LPTS'];
				$pts2 = $game['WPTS'];
				$diff2 = $pts2 - $pts1;
				$diff1 = $pts1 - $pts2;
				if ($game['gamenum'] != $cgamenum) {
					echo '<tr><td rowspan="'.$game['game_per_round'].'">'.$game['gamenum'].'</td><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" ><b>'.$pts2.'-'.$pts1.'</b></td>';
					$cgamenum = $game['gamenum'];
					$ccount = 1;
				}else{
					echo '<tr><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" width="30"><b>DEF</b></td><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" ><b>'.$pts2.'-'.$pts1.'</b></td>';
					$ccount++;
				}
			}else{
				//tie
				$pts1 = $game['LPTS'];
				$pts2 = $game['WPTS'];
				$diff2 = $pts2 - $pts1;
				$diff1 = $pts1 - $pts2;
				if ($game['gamenum'] != $cgamenum) {
					echo '<tr><td rowspan="'.$game['game_per_round'].'">'.$game['gamenum'].'</td><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" width="30"><b>TIE</b></td><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" ><b>'.$pts2.'-'.$pts1.'</b></td>';
					$cgamenum = $game['gamenum'];
					$ccount = 1;
				}else{
					echo '<tr><td style="font-size:0.8em">'.$team2.' ('.$diff2.')</td><td style="font-size:0.8em" width="30"><b>TIE</b></td><td style="font-size:0.8em">'.$team1.' ('.$diff1.')</td><td style="font-size:0.8em" ><b>'.$pts2.'-'.$pts1.'</b></td>';
					$ccount++;
				}
			}
			
			
				echo '</tr>';
			}
				echo '</table></td>';
				
			}//end rows vs no rows
		}//end for draws
		
		echo '</tr></table>';
		if($mode == ""){
			echo '<BR><BR>';
			echo '<a href="index.php">WB Home</a> ';
		}

	?>
   </center>
   </body>
 </html>