<?php // ptcheckuser.php
include_once 'ptfunctions.php';

if (isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$query = "SELECT * FROM ptmembers WHERE user='$user'";

	if (mysql_num_rows(queryMysql($query)))
		echo "<font color=red>&nbsp;&larr;
			 Sorry, already taken</font>";
	else echo "<font color=green>&nbsp;&larr;
			 Username available</font>";
}
?>
