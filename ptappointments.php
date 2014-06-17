<?php // ptappointments.php
include_once 'ptheader.php';

if (!isset($_SESSION['user']))
	die("<br /><br />You need to login to view this page");
$user = $_SESSION['user'];

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else $view = $user;

if (isset($_POST['delete']) && isset($_POST['delTutorPID']) && 
    isset($_POST['delStarttime']) && isset($_POST['delDate']) && 
    isset($_POST['delSubject']) )
{
	$delTutorPID = $_POST['delTutorPID'];
        $delStarttime = $_POST['delStarttime'];
        $delDate  = $_POST['delDate'];
        $delSubject = $_POST['delSubject'];
	$query = "DELETE FROM ptappointments WHERE tutorPID= '$delTutorPID' 
                AND starttime= '$delStarttime' 
                AND date= '$delDate' AND subject= '$delSubject' ";
        queryMysql($query);

	if (!queryMysql($query))	
		echo "DELETE failed: $query<br />" .
		mysql_error() . "<br /><br />";
}

if (isset($_POST['tutorPID']) &&
	isset($_POST['starttime']) &&
	isset($_POST['date'])&&
	isset($_POST['subject']))
{
	$tutorPID   = $_POST['tutorPID'];
	$starttime  = $_POST['starttime'];
	$endtime    = $_POST['starttime'];
	$user       = $_SESSION['user'];
	$date       = $_POST['date'];
        $subject    = $_POST['subject'];
        
        $fail  = validate_tutorPID($tutorPID);
        $fail .= validate_starttime($starttime);
        $fail .= validate_date($date);
        $fail .= validate_subject($subject);
        if ($fail == "") {
            $aquery = "SELECT user FROM ptappointments WHERE tutorPID='$tutorPID'
                    AND starttime= '$starttime' AND date= '$date' 
                    AND subject= '$subject'";
            $aresult = queryMysql($aquery);
            $arow = mysql_fetch_row($aresult);
            
            if (mysql_num_rows(queryMysql($aquery)))
		echo "<br /><br />
                    <font color=red>&nbsp;
                    SORRY, APPOINTMENT ALREADY TAKEN by $arow[0]<br />
                    SEND $arow[0] a REQUEST TO JOIN SESSION.</font>";
            else {	
                $query = "INSERT INTO ptappointments VALUES
		('$tutorPID', '$starttime', '$endtime', '$user', '$date', '$subject')";
        
                if (!queryMysql($query))
                    echo "INSERT failed: $query<br />" .
                    mysql_error() . "<br /><br />";
            }
}
}
echo <<<_END
<br /><br />Make an appointment here:<br /><br />
<!-- The HTML section -->
<link rel="stylesheet"
	type="text/css" href="fonts-min.css" />
<link rel="stylesheet"
	type="text/css" href="assets/skins/sam/calendar.css" /> 
<script src="yahoo-dom-event.js"></script>
<script src="calendar-min.js"></script>
</head><body class="yui-skin-sam">
<div id="cal1Container"></div>
<script>
YAHOO.namespace("example.calendar");
YAHOO.example.calendar.init = function() {
	YAHOO.example.calendar.cal1 =
		new YAHOO.widget.Calendar("cal1", "cal1Container",
			{ MULTI_SELECT: true } )
	YAHOO.example.calendar.cal1.render()
}
YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init)

</script>
</body>

_END;
echo <<<_END
<style>.signup { border: 1px solid #999999;
	font: normal 14px helvetica; color:#444444; }</style>
<script type="text/javascript">
function validate(form)
{
	fail  = validateTutorPID(form.tutorPID.value)
	fail += validateStarttime(form.starttime.value)
	fail += validateDate(form.date.value)
	fail += validateSubject(form.subject.value)
	if (fail == "") return true
	else { alert(fail); return false }
}
</script></head><body>
<br /><br /><br /><br />

<form method="post" action="ptappointments.php" onSubmit="return validate(this)"><pre>
       Subject  <select name="subject" size="1">
        <option value="">- Choose -</option>
        <option value="MATH">MATH</option>
        <option value="CALCULUS">CALCULUS</option>
        <option value="STATISTICS">STATISTICS</option>
        <option value="PHYSICS">PHYSICS</option>
    </select>
    Start time  <select name="starttime" size="1">
        <option value="">- Choose -</option>
        <option value="08:00:00">8:00 AM</option>
        <option value="09:00:00">9:00 AM</option>
        <option value="10:00:00">10:00 AM</option>
        <option value="11:00:00">11:00 AM</option>
        <option value="12:00:00">12:00 PM</option>
        <option value="01:00:00">1:00 PM</option>
        <option value="02:00:00">2:00 PM</option>
        <option value="03:00:00">3:00 PM</option>
        <option value="04:00:00">4:00 PM</option>
        <option value="05:00:00">5:00 PM</option>
        <option value="06:00:00">6:00 PM</option>
    </select>
          Date  <input type="date" name="date" />
         Tutor  <select name="tutorPID" size="1">
        <option value="">- Choose -</option>
        <option value="Camilo Morales">Camilo Morales</option>
        <option value="Lesly McCarthy">Lesly McCarthy</option>
        <option value="Harvey Jordon">Harvey Jordon</option>
        <option value="Tommy Cohen">Tommy Cohen</option>
        <option value="Leonard Jackson">Leonard Jackson</option>
    </select>
             <input type="submit" value="ADD APPOINTMENT" />
</pre></form>

<!-- The JavaScript section -->

<script type="text/javascript">
function validateTutorPID(field) {
	if (field == "") return "No TUTOR was entered.\\n"
	return ""
}

function validateStarttime(field) {
	if (field == "") return "No time was entered.\\n"
	return ""
}

function validateDate(field) {
	if (field == "") return "No DATE was entered.\\n"
	else if (field.length != 10)
		return "Correct DATE format: YYYY-MM-DD.\\n"
	return ""
}

function validateSubject(field) {
	if (field == "") return "No subject was entered.\\n"
	return ""
}

</script></body></html>
_END;

function validate_tutorPID($field) {
	if ($field == "") return "No TUTOR was entered<br />";
	return "";
}

function validate_starttime($field) {
	if ($field == "") return "No time was entered<br />";
	return "";
}

function validate_date($field) {
	if ($field == "") return "No DATE was entered<br />";
	else if (strlen($field) != 10)
		return "Correct DATE format: YYYY-MM-DD<br />";
	return "";
}

function validate_subject($field) {
	if ($field == "") return "No subject was entered<br />";
	return "";
}


$query = "SELECT * FROM ptappointments WHERE user='$user'";
$result = queryMysql($query);

if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);

for ($j = 0 ; $j < $rows ; ++$j)
{
	$row = mysql_fetch_row($result);
	echo <<<_END
<pre>
<br />
    Tutor $row[0]
Appt time $row[1]
     Date $row[4]
  Subject $row[5]
</pre>
<form action="ptappointments.php" method="post">
<input type="hidden" name="delete" value="yes" />
<input type="hidden" name="delTutorPID" value="$row[0]" />
<input type="hidden" name="delStarttime" value="$row[1]" />
<input type="hidden" name="delDate" value="$row[4]" />
<input type="hidden" name="delSubject" value="$row[5]" />
<input type="submit" value="DELETE APPOINTMENT" /></form>
_END;
}

?>