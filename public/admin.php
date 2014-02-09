<?php

require("votes.inc.php");



// Pull query string

if(isset($_GET['enable'])) {

  $enable = $_GET['enable'];

}

else {

  $enable = -1;

}



// Connect to database

$mysqli = new mysqli('localhost', 'username', 'password', 'database');

if ($mysqli->connect_errno) {

    printf("Connect failed: %s\n", $mysqli->connect_error);

    exit();

}



for($i = 1; $i <= $numVotes; $i++) {

  $query = "SELECT count(*) AS 'count' FROM votes WHERE vote = '$i'";

  $result = $mysqli->query($query);

  while($obj = $result->fetch_object())

    $vote[$i] = $obj->count; 

}



if($enable == "5") {

  $query = "UPDATE setting SET value='1' WHERE setting = 'enabled'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on enable update for 5 minutes";



  $query = "UPDATE setting SET value='";

  $newtime = time() + (60 * 5);

  $query .= $newtime;

  $query .= "' WHERE setting = 'timetoquit'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on time update for 5 minutes";



  print "<div>Updated to open in 5 minutes! ($newtime)</div>\n\n";

}

else if($enable == "10") {

  $query = "UPDATE setting SET value='1' WHERE setting = 'enabled'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on enable update for 10 minutes";



  $query = "UPDATE setting SET value='";

  $newtime = time() + (60 * 10);

  $query .= $newtime;

  $query .= "' WHERE setting = 'timetoquit'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on update for 10 minutes";



  print "<div>Updated to open in 10 minutes! ($newtime)</div>\n\n";

}

else if($enable == "0") {

  $query = "UPDATE setting SET value='0' WHERE setting = 'enabled'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on disable update";



  print "<div>Disabled!</div>\n\n";

}





$query = "SELECT value FROM setting WHERE setting = 'enabled'";

$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_NUM);

$enabled = $row[0];



$query = "SELECT value FROM setting WHERE setting = 'timetoquit'";

$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_NUM);

$time_to_quit = (int) $row[0];









?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="refresh" content="30;url=http://www.sarahwithee.com/swe/admin.php" /> 

<title>Mr. Engineer Voting Results!</title>

<style type="text/css">

.header {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 36px;

  color: #0000ff;

}

body {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 14px;

  color: #000000;

  font-style: none;

}



.vote {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 28px;

  color: #000000;

  font-style: none;

}



.disclaimer {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 18px;

  color: #000000;

  font-style: none;

}



</style>

</head>



<body>



<h2>SWE Mr. Engineer Admin system</h2>



<div>Voting system: Currently <?php 

if($enabled == "0") print "disabled";

else if ($enabled == "1") print "enabled";

else print "Uh, not sure..."; ?></div>



<div><a href="admin.php?enable=5">Enable for 5 minutes</a></div>

<div><a href="admin.php?enable=10">Enable for 10 minutes</a></div>



<div><a href="admin.php?enable=0">Disable now</a></div>



<div>&nbsp;</div>



<div><a href="admin.php">Refresh</a></div>

<div>Time now: <?php

print date("h:i:s a",time());

?></div>



<div>Time until disabled: <?php

print date("h:i:s a",$time_to_quit);

?>





</div>

<div>&nbsp;</div>



<div>Current results:</div>

<?php

for($i = 1; $i <= $numVotes; $i++) {

  $name[$i] = str_replace("<br />", " ", $name[$i]);

  $name[$i] = str_replace("&nbsp;", "", $name[$i]);

  print "<div>$i: " . $name[$i] . " - " . $vote[$i] . "</div>\n";

} ?>



</body>

</html>