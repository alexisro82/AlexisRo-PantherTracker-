<?php // ptheader.php
include 'ptfunctions.php';
session_start();

if (isset($_SESSION['user']))
{
	$user = $_SESSION['user'];
	$loggedin = TRUE;
}
else $loggedin = FALSE;

echo "<html><head><title>$appname";
if ($loggedin) echo " ($user)";

echo "</title></head><body><font face='verdana' size='4'>";
echo "<h2>$appname</h2>";

if ($loggedin)
{
	echo "<b>$user</b>:
		 <a href='ptmembers.php?view=$user'>Home</a> |
		 <a href='ptmembers.php'>Notifications</a> |
		 <a href='ptfriends.php'>Friends</a> |
		 <a href='ptmessages.php'>Messages</a> |
		 <a href='ptprofile.php'>Profile</a> |
                 <a href='ptappointments.php'>Appointments</a> |
		 <a href='ptlogout.php'>Log out</a>";
}
else
{
	echo "<a href='ptindex.php'>Home</a> |
		 <a href='ptsignup.php'>Sign up</a> |
		 <a href='ptlogin.php'>Log in</a>";
}
?>
