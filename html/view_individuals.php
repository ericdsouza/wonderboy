<?php
  session_start();
 	$_SESSION['user'] = 'view';
?>
<?php include ("admin/admin.php"); ?>
<?php include ("admin/add_individuals.php"); ?>