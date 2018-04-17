<html>
	<head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  	<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    <title>View Results - Poker</title>
  </head>
  <body>
  	<center>
  	<h1>WB 2019 Poker Results</h1>	
  		
  <?php include ("admin/admin.php"); ?>
	<?php
		$result = mysql_query("SELECT poker_results.SID, poker_results.PID AS PID, poker_results.Points, participants.nickname
FROM poker_results
LEFT OUTER JOIN participants ON poker_results.PID = participants.PID
WHERE poker_results.WID =1
ORDER BY poker_results.SID");
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0) {
  		$count = 1;
  		echo '<table id="myTable" class="tablesorter" border="0" cellpadding="5"><th colspan="2" align="center">Final Table</th><th align="center">Points</th>';
  		while ($row = mysql_fetch_array($result)){
  			if ($count > 6) {
  				if ($count == 7) {
  					echo '<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>';
						echo '<tr><td colspan="3" align="center" style="background-color:#CDCDCD;"><b>Wild Card Table</b></td></tr>';
  				}
  				if ($row['PID'] == 0) {
	  				echo '<tr><td>Seat '.$count.'</td><td colspan=2>TBD</td></tr>';
	  			}else{
	  				echo '<tr><td>Seat '.$count.'</td><td colspan=2>'.$row['nickname'].'</td></tr>';
	  			}
  			}else{
	  			if ($row['PID'] == 0) {
	  				echo '<tr><td>Seat '.$count.'</td><td>TBD</td><td align="center">'.$row['Points'].'</td></tr>';
	  			}else{
	  				echo '<tr><td>Seat '.$count.'</td><td>'.$row['nickname'].'</td><td align="center">'.$row['Points'].'</td></tr>';
	  			}
  		}
  			$count++;
  		}
  		echo '</table>';
  	}else{
  		echo 'This event has not started yet';
  	}
	?>
		<BR><BR>
		<a href="index.php">WB Home</a>
		</center>
	</body>
	<head>
  	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    </head>
 </html>