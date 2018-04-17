<html>
  <head>
    <title>Results - Tug</title>
    <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
  </head>
  <body>
  	<center>
  	
  	<?php include ("admin/admin.php"); ?>
  	<?php 
  		$result = mysql_query("SELECT EID, event_name, shortname, draws from event where shortname = 'tug'");
  		
  		$row = mysql_fetch_array($result);
  		$eid = $row['EID'];
  		//initialize bogus IDs
  		$tugTID[1] = 99999;
  		$tugTID[2] = 99999;
  		  		
  		echo "<h1>WB 2019 Results: " . $row['event_name'] . "</h1>";
  		echo '<table id="myTable" class="tablesorter" border = "2" cellpadding = "2">';
  		
  		
  		for($i = 1; $i <= $row['draws']; $i++){
  			echo '<th>Team ' . $i . '</th>';
  			}
  			echo "<tr>";
		for($i = 1; $i <= $row['draws']; $i++){ 
					//for each team 
					echo '<td><table id="myTable" class="tablesorter">';
					$result2 = mysql_query("SELECT TID, EID, PID from teams where draw = $i and EID = $eid order by TID");
					$count = 1;
					while($row2 = mysql_fetch_array($result2)){
						$tid = $row2['TID'];
						$tugTID[$i] = $tid;
						$teamExists = true;
						//there are players already added to this team
						$pid = $row2['PID'];
						$result3 = mysql_query("SELECT nickname from participants where PID = $pid");
						$row3 = mysql_fetch_array($result3);						  		
							//display player		
							if ($count % 2) {	
  								echo "<tr><td>";
  								} else {
  								echo '<tr bgcolor="lightgrey"><td>'; 	
  								}
  							echo $count;
  							$count++;
  							echo "</td><td>";
  							echo $row3['nickname'];
  							echo '</td></tr>';			
  					}//end teams already in draw
  					echo '</table>';
  		}//end draws
  		echo "</tr>";					
 		$resultT1 = mysql_query("SELECT WIN_TID from tug_results where TID1 = $tugTID[1] and TID2 = $tugTID[2] and WID = 1");
 		$num_rows = mysql_num_rows($resultT1);
  		if($num_rows == 0){
  				echo '<tr><td colspan = "2" align="center"> The scores for this event have not been recorded yet. ';
  			}else{
  				echo '<tr><td align="center">';
  				$rowWinner = mysql_fetch_array($resultT1);
  				if ($rowWinner['WIN_TID'] == $tugTID[1]) {
  					echo 'WINNER (2pts)</td><td align="center">LOSER (0pts)</td>';
  					}else{
					echo 'LOSER (0pts)</td><td align="center">WINNER (2pts)</td>';  					
  					}
  		
  			}
  		echo "</td></tr></table></td>";
  			?>
  	</table>

  	<BR><BR>
  <a href="index.php">WB Home</a> 
  </center>
 	
  </body>
</html>