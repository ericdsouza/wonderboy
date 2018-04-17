<html>
  <head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  	<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    <title>Wonderboy 2019 Standings</title>
  </head>
   <body>
   	<font face="Arial" size="2">
  	<center>
  		<h1>Wonderboy 2019 Standings</h1>
  		<a href="index.php">WB Home</a>
  		<table id="myTable" class="tablesorter" width=50%>
  			<font face="Arial" size="3"><th bgcolor="#BFBFBF"></th><th bgcolor="#BFBFBF"></th><th bgcolor="#BFBFBF"><b>Special</b></th><th bgcolor="#BFBFBF"><b>Tug</b></th><th bgcolor="#BFBFBF"><b>Saw</b></th><th bgcolor="#BFBFBF"><b>Crokinole</b></th><th bgcolor="#BFBFBF"><b>Euchre</b></th><th bgcolor="#BFBFBF"><b>Poker</b></th><th bgcolor="#BFBFBF"><b>Darts</b></th><th bgcolor="#BFBFBF"><b>Ping<BR>Pong</b></th><th bgcolor="#BFBFBF"><b>TOTAL</b></th></font>
<?php include ("admin/admin.php"); ?>
<?php
	//generate the PIDs by order of total points in event_participant_points for current year
	$result = mysql_query("select event_participant_points.PID as PID, Sum(Points) as Total, ( select points from event_participant_points as b where eid = 3 and b.PID = event_participant_points.PID) as poker, (select count(*) from poker_results c where c.PID = event_participant_points.PID) as made_wildcard from event_participant_points, participants where event_participant_points.PID = participants.PID group by PID order by Total DESC, poker DESC, made_wildcard DESC");
	$place = 1;
	$disp_place = 0;
	$prev_total = 10000;
	$flag = 0;
	while($row_PID_TTL = mysql_fetch_array($result))
  				{
  				if ($flag == 0 && $row_PID_TTL['Total'] == 0 && $place != 1) {
  					//1st time sow line.  But only if $place != 1 or else everybody has 0 pts
  					$flag = 1;
  					echo '<tr><td align="center" colspan="11"><img src="images/straight.png"></td></tr>';
  				}
  				
  				//increment the place if the previous total is different
						  if ($row_PID_TTL['Total'] != $prev_total || $disp_place == 1){
							 $disp_place = $place;
							 $prev_total = $row_PID_TTL['Total'];
						  }
						  
					//we have the PIDs sorted and their totals.  get the name and the special
					$result2 = mysql_query("SELECT fname, nickname, lname, special from participants where PID = $row_PID_TTL[PID]");
					$row_NAME_PLUS_SPECIAL = mysql_fetch_array($result2);
					echo '<tr><td width=50>'.$disp_place.'</td><td width=250><b><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_NAME_PLUS_SPECIAL['fname'].' '.$row_NAME_PLUS_SPECIAL['nickname'].' '.$row_NAME_PLUS_SPECIAL['lname'].'<b></td>';
					
					//Now display the events according to the spreadsheet
					//SPECIAL
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 7 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					if ($row_NAME_PLUS_SPECIAL['special'] == 1) {
						echo '<td align="center" width=50><b>'.$row_SPECIAL['Points'].'</b></td>';
					}else{
						echo '<td align="center" width=50><b>no</b></td>';
					}
					
					//TUG
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 6 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					
					//SAW
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 4 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					
					//CROKE
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 8 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					
					//EUCHRE
					$ptsleftflag = 0;
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 2 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					//determine if there are still points available in this event
					$resultAVAIL = mysql_query("SELECT points_left from still_alive_event, teams where still_alive_event.TID = teams.TID and teams.PID = $row_PID_TTL[PID] and still_alive_event.EID = 2");
					if ( mysql_num_rows($resultAVAIL) > 0 ) {
						$ptsleftflag = 1;
						//echo '<td align="center" bgcolor="#FFFF00">'.$row_SPECIAL['Points'].'</td>';
						echo '<td align="center" width=50><span style="background-color:#FFFF00">'.$row_SPECIAL['Points'].'</span></td>';
					}else{
						echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					}	
					
					//POKER
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 3 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					$resultAVAIL = mysql_query("SELECT points_left from still_alive_event where PID = $row_PID_TTL[PID] and EID = 3");
					if ( mysql_num_rows($resultAVAIL) > 0 ) {
						$ptsleftflag = 1;
						//echo '<td align="center" bgcolor="#FFFF00">'.$row_SPECIAL['Points'].'</td>';
						echo '<td align="center" width=50><span style="background-color:#FFFF00">'.$row_SPECIAL['Points'].'</span></td>';
					}else{
						echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					}	
					
					//DARTS
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 5 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					$resultAVAIL = mysql_query("SELECT points_left from still_alive_event, teams where still_alive_event.TID = teams.TID and teams.PID = $row_PID_TTL[PID] and still_alive_event.EID = 5");
					if ( mysql_num_rows($resultAVAIL) > 0 ) {
						$ptsleftflag = 1;
						//echo '<td align="center" bgcolor="#FFFF00">'.$row_SPECIAL['Points'].'</td>';
						echo '<td align="center" width=50><span style="background-color:#FFFF00">'.$row_SPECIAL['Points'].'</span></td>';
					}else{
						echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					}	
					
					//PONG
					$result3 = mysql_query("SELECT Points from event_participant_points where EID = 1 and PID = $row_PID_TTL[PID]");
					$row_SPECIAL = mysql_fetch_array($result3);
					$resultAVAIL = mysql_query("SELECT points_left from still_alive_event, teams where still_alive_event.TID = teams.TID and teams.PID = $row_PID_TTL[PID] and still_alive_event.EID = 1");
					if ( mysql_num_rows($resultAVAIL) > 0 ) {
						$ptsleftflag = 1;
						//echo '<td align="center" bgcolor="#FFFF00">'.$row_SPECIAL['Points'].'</td>';
						echo '<td align="center" width=50><span style="background-color:#FFFF00">'.$row_SPECIAL['Points'].'</span></td>';
					}else{
						echo '<td align="center" width=50>'.$row_SPECIAL['Points'].'</td>';
					}	
					
					//TOTAL
					if ($ptsleftflag == 1){
						echo '<td align="center" width=50><span style="background-color:#FFFF00"><b>'.$row_PID_TTL['Total'].'</b></span></td>';
					}else{
					echo '<td align="center" width=50>'.$row_PID_TTL['Total'].'</td>';
				}
				echo '</tr>';
				$place++;
				}//end row_PID_TTL
				
					
 ?>
 	<tr><td colspan="11">NOTE:  The <span style="background-color:#FFFF00">&nbsp&nbsp&nbsp</span> indicates the player can still earn points in the event</td></tr>
 	</table>
 	<BR><BR>
  <a href="index.php">WB Home</a>
  	</center>
  </font>
  </body>
  <head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  </head>
</html>