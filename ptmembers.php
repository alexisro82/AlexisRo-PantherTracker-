<?php // ptmembers.php
include_once 'ptheader.php';

if (!isset($_SESSION['user']))
	die("<br /><br />You must be logged in to view this page");
$user = $_SESSION['user'];

if (isset($_GET['view']))
{
	$view = sanitizeString($_GET['view']);
	
	if ($view == $user) $name = "Your";
	else $name = "$view's";
	
	echo "<h3>$name Page</h3>";
	showProfile($view);
	echo "<a href='ptmessages.php?view=$view'>$name Messages</a><br />";
	die("<a href='ptfriends.php?view=$view'>$name Friends</a><br />");
}

if (isset($_GET['add']))
{
	$add = sanitizeString($_GET['add']);
	$query = "SELECT * FROM ptfriends WHERE user='$add'
			  AND friend='$user'";
	
	if (!mysql_num_rows(queryMysql($query)))
	{
		$query = "INSERT INTO ptfriends VALUES ('$add', '$user')";
		queryMysql($query);
	}
}
elseif (isset($_GET['remove']))
{
	$remove = sanitizeString($_GET['remove']);
	$query = "DELETE FROM ptfriends WHERE user='$remove'
			  AND friend='$user'";
	queryMysql($query);
}

$result = queryMysql("SELECT user FROM ptmembers ORDER BY user");
$num = mysql_num_rows($result);
echo "<h3>Other Members</h3><ul>";

for ($j = 0 ; $j < $num ; ++$j)
{
	$row = mysql_fetch_row($result);
	if ($row[0] == $user) continue;
	
	echo "<li><a href='ptmembers.php?view=$row[0]'>$row[0]</a>";
	$query = "SELECT * FROM ptfriends WHERE user='$row[0]'
			  AND friend='$user'";
	$t1 = mysql_num_rows(queryMysql($query));
	
	$query = "SELECT * FROM ptfriends WHERE user='$user'
			  AND friend='$row[0]'";
	$t2 = mysql_num_rows(queryMysql($query));
	$follow = "Join Session";

	if (($t1 + $t2) > 1)
	{
		echo " &harr; is a mutual friend (Session Joined)";
	}
	elseif ($t1)
	{
		echo " &larr; you are requesting to join a session";
	}
	elseif ($t2)
	{
		$follow = "Accept";
		echo " &rarr; is requesting to join a session";
	}
	
	if (!$t1)
	{
		echo " [<a href='ptmembers.php?add=".$row[0] . "'>$follow</a>]";
	}
	else
	{
		echo " [<a href='ptmembers.php?remove=".$row[0] . "'>Drop</a>]";
	}
}
?>
