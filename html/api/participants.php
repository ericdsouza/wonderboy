<html>
  <head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    <title>Participants</title>
  </head>
  <body>
  	<center>
  	<?php include ("admin/admin.php"); ?>
  	<?php 
  		$result = mysql_query("SELECT name from wonderboy where WID = 1");
  		
  		while($row = mysql_fetch_array($result))
  			{
  				echo "<h1>" . $row['name'] . " Participants</h1>";
  			}
  			echo '<a href="index.php">WB Home</a> <BR><BR>';
  	?>
	<img src="images/Wonderboy_VG_pan.jpg">
  	<table id="attendTable" class="tablesorter" width="500"><tr><td align="center"><h2>Name</h2></td></tr>
  		<?php
  			$result = mysql_query("SELECT fname, nickname, lname, shortname, PID, special from participants where WID = 1 order by lname");
  			
  			while($row = mysql_fetch_array($result))
  				{
  					echo '<tr><td width="200" align="center"><font size="3">';
  					echo $row['fname'] . " '" . $row['nickname'] . "' " . $row['lname'];
  					echo "</font></td>";
  					echo "</tr>";
  				}
  			?>
  			</table>
  <BR><BR>
  <a href="index.php">WB Home</a>
  </center>
 	
  </body>
  <head>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  </head>
</html>