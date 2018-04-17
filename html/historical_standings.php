<html>
  <head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  	<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    <title>Wonderboy Historical Standings</title>
    <script type="text/javascript" src="libjs/jquery-1.8.3.js"></script> 
	  <script type="text/javascript" src="libjs/jquery.tablesorter.js"></script> 
	  <script type="text/javascript">
  		$(document).ready(function() 
    	{ 
        $("#myTable").tablesorter( {sortInitialOrder: 'desc', sortList: [[3,1]]} ); 
    	} 
			); 
		</script>
  </head>
  <body>
  	
  	<center>
  	<h1>Wonderboy Historical Standings</h1>
  	<a href="index.php">WB Home</a>
  <?php include ("admin/admin.php"); ?>
<?php
//retrieve the historical standings and add the current year points
$result = mysql_query("SELECT view_historical_standings.nickname, view_historical_standings.PID, view_historical_standings.Total + IFNULL( view_current_year_standings.Total, 0 ) AS Total
FROM view_historical_standings
LEFT OUTER JOIN view_current_year_standings ON view_historical_standings.PID = view_current_year_standings.PID
ORDER BY Total DESC , view_historical_standings.PID DESC ");

//get the number of years person has played so we can get averages

//get the previous year rankings
$result40 = mysql_query("select @row_number := NULL;");
$result41 = mysql_query("select nickname, PID, Total, @row_number := IFNULL(@row_number, 0) + 1 AS row_number from view_historical_standings");
$histcount = 1;
$prev_total = 99999;
$prevrownum = 0;
$tie_flag = 0;
while ($rowStand= mysql_fetch_array($result41)) {
	
	//for tied players, do not increment the row_number
	if ($rowStand['Total'] == $prev_total) {
		if ($tie_flag != 0){
			 $lastyrpos = $prevrownum - $tie_flag;
			 $tie_flag++;
		}else{
		   $lastyrpos = $prevrownum;
		   $tie_flag++;
	  }
	} else {
		$lastyrpos = $rowStand['row_number'];
		$tie_flag = 0;
	}
	//build array of PID and previous year rank
	$histPID[$histcount] = $rowStand['PID'];
	$histRANK[$histcount] = $lastyrpos;
	$histcount++;
	$prev_total = $rowStand['Total'];
	$prevrownum = $rowStand['row_number'];
}
  $doavg = 0;
	$place = 1;
	$disp_place = 0;
	$prev_total = 10000;
	if (!isset($_GET["view"])) {
		echo '<br></center><span style="background-color:#8dbdd8"><a href="historical_standings.php">Total</a></span> | <a href="historical_standings.php?view=avg">Yearly Avg</a><center>';
	} else {
		echo '<br></center><a href="historical_standings.php">Total</a> | <span style="background-color:#8dbdd8"><a href="historical_standings.php?view=avg">Yearly Avg</a></span><center>';
		$doavg = 1;
	}
	
	echo '<table id="myTable" class="tablesorter" width=100%>';
	if ($doavg == 0) {
	echo '<thead><tr><th></th><th><b>Player</b></th><th><b>Yrs</b></th><th align="center"><b>Points</b></th><th><b>Pts This Yr</b></th><th><b>Tug</b></th><th><b>Saw</b></th><th><b>Crokinole</b></th><th><b>Euchre</b></th><th><b>Poker</b></th><th><b>Pong</b></th><th><b>Darts</b></th><th><b>Special</b></th></tr></thead>';
echo '<tbody>'; } else {
	echo '<thead><tr><th></th><th><b>Player</b></th><th><b>Yrs</b></th><th align="center"><b>Points</b></th><th><b>Tug</b></th><th><b>Saw</b></th><th><b>Crokinole</b></th><th><b>Euchre</b></th><th><b>Poker</b></th><th><b>Pong</b></th><th><b>Darts</b></th><th><b>Special</b></th></tr></thead>';
echo '<tbody>';
}
	
	while($row_PID_TTL = mysql_fetch_array($result)){
		
		          //increment the place if the previous total is different
						  if ($row_PID_TTL['Total'] != $prev_total || $disp_place == 1){
							 $disp_place = $place;
							 $prev_total = $row_PID_TTL['Total'];
						  }
						  
							//retrieve the differential from last year
							$showmove = "";
							for ($z=1;$z<=sizeof($histPID);$z++) {
								if ($row_PID_TTL['PID'] == $histPID[$z]) {
									$move_up_or_down = $histRANK[$z] - $disp_place;
									if ($move_up_or_down > 0) {
										$showmove = '<img src="images/up.jpg" height="10" width="10"><span style="color:#00FF00"><b>'.$move_up_or_down.'</b></span>';
									}
								}
							}
							//get the events points
							$events = mysql_query("SELECT Special, Pong, Euchre, Poker, Saw, Darts, Tug, Croke from archive_events_points_summary where PID = $row_PID_TTL[PID]");
							$pts_per_event = mysql_fetch_array($events);
							
							//get the number of years for the total
							$yrsTTL = mysql_query("select count(*) as yrTTL from yearly_results where PID = $row_PID_TTL[PID]");
							$yrsTTL_fetch = mysql_fetch_array($yrsTTL);
							
							//get the number of years for croke that started in 2013
							$yrsCrokeQ = mysql_query("SELECT distinct WID from archive_event where EID = 8");
							$yrsCroke = mysql_num_rows($yrsCrokeQ);
							
							//get the number of years for events (this is because there are 2 years that don't have events)
							$yrsEvent = mysql_query("SELECT WID, PID from archive_event_participant_points where PID = $row_PID_TTL[PID] group by WID");
							$numYrsEvent = mysql_num_rows($yrsEvent);
							
							$result2 = mysql_query("SELECT sum(Points) as Total from event_participant_points where PID = $row_PID_TTL[PID]");
							$row_EVENT_TTL = mysql_fetch_array($result2);
							
							//
						  
							if ($doavg == 0) {
								if ($row_EVENT_TTL['Total'] != 0){
									echo '<tr><td>'.$disp_place.'</td><td><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_PID_TTL['nickname'].' '.$showmove.'</td>'.'<td align="center">'.$yrsTTL_fetch['yrTTL'].'</td>'.'<td align="center">'.$row_PID_TTL['Total'].'</td><td align="center">'.$row_EVENT_TTL['Total'].'</td>'.'<td align="center">'.$pts_per_event['Tug'].'</td>'.'<td align="center">'.$pts_per_event['Saw'].'</td>'.'<td align="center">'.$pts_per_event['Croke'].'</td>'.'<td align="center">'.$pts_per_event['Euchre'].'</td>'.'<td align="center">'.$pts_per_event['Poker'].'</td>'.'<td align="center">'.$pts_per_event['Pong'].'</td>'.'<td align="center">'.$pts_per_event['Darts'].'</td>'.'<td align="center">'.$pts_per_event['Special'].'</td>'.'</tr>';
								}else{
									echo '<tr><td>'.$disp_place.'</td><td><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_PID_TTL['nickname'].'<td align="center">'.$yrsTTL_fetch['yrTTL'].'</td>'.'<td align="center">'.$row_PID_TTL['Total'].'</td><td></td>'.'<td align="center">'.$pts_per_event['Tug'].'</td>'.'<td align="center">'.$pts_per_event['Saw'].'</td>'.'<td align="center">'.$pts_per_event['Croke'].'</td>'.'<td align="center">'.$pts_per_event['Euchre'].'</td>'.'<td align="center">'.$pts_per_event['Poker'].'</td>'.'<td align="center">'.$pts_per_event['Pong'].'</td>'.'<td align="center">'.$pts_per_event['Darts'].'</td>'.'<td align="center">'.$pts_per_event['Special'].'</td>'.'</tr>';
								}
						} else {
									echo '<tr><td>'.$disp_place.'</td><td><a href="archives/profile.php?PID='.$row_PID_TTL['PID'].'">'.$row_PID_TTL['nickname'].'</td>
									<td align="center">'.$yrsTTL_fetch['yrTTL'].'</td>'.'<td align="center">'.round($row_PID_TTL['Total']/$yrsTTL_fetch['yrTTL'],1).'</td>'.'<td align="center">'.round($pts_per_event['Tug']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Saw']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Croke']/$yrsCroke,2).'</td>'.'<td align="center">'.round($pts_per_event['Euchre']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Poker']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Pong']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Darts']/$numYrsEvent,2).'</td>'.'<td align="center">'.round($pts_per_event['Special']/$numYrsEvent,2).'</td>'.'</tr>';
								
						}
						$place++;
					}
					
					echo '</tbody></table><BR><BR>';
					
	?>
	
	<a href="index.php">WB Home</a>
</center>
</body>
</html>
	