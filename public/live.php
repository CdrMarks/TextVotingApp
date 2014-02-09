<?php

require("graphs.inc.php");

require("votes.inc.php");



// Connect to database

$mysqli = new mysqli('localhost', 'username', 'passowrd', 'database');

if ($mysqli->connect_errno) {

    printf("Connect failed: %s\n", $mysqli->connect_error);

    exit();

}



for($i = 1; $i <= $numVotes; $i++) {

  $query = "SELECT count(*) AS 'count' FROM vote WHERE vote = '$i'";

  $result = $mysqli->query($query);

  while($obj = $result->fetch_object())

    $vote[$i] = $obj->count; 

}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="refresh" content="2" /> 

<title>Mr. Engineer Voting Results!</title>

<style type="text/css">

.header {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 36px;

  color: #0000ff;

}

body {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 24px;

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



.time {

	font-family: Calibri, Verdana, Geneva, sans-serif;

  font-size: 15px;

  color: #000000;

  font-style: none;

}



</style>

</head>



<body>

<table border="0">

<tr>

<td width="10%"><img src="sce-stack_web_200px.jpg" /></td>

<td width="80%"><div align="center" class="header"><strong><em>Mr. Engineer</em> People's Choice Vote</strong></div></td>

<td width="10%"><img src="swe_logo.jpg" /></td>

</tr>

</table>



<?php

$query = "SELECT value FROM setting WHERE setting = 'enabled'";

$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_NUM);

$enabled = $row[0];



$query = "SELECT value FROM setting WHERE setting = 'timetoquit'";

$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_NUM);

$timetoquit = (int) $row[0];

$timeleft = $timetoquit - time();

$timeleftmin = abs(floor($timeleft / 60));

$timeleftsec = $timeleft % 60;





//print "Time to quit: $timetoquit<br />";

//print "Time left to vote: $timeleft<br />";

//print "Time left min: $timeleftmin<br />";

//print "Time left sec: $timeleftsec<br />";



// If it's past time, update script to disable

if($timeleft <= 0) {

  $query = "UPDATE setting SET value='0' WHERE setting = 'enabled'";

  $result = $mysqli->query($query);

  if($result == false)

    print "ERROR on disable";

}



// Not enabled? Immediately send invalid response

if($enabled == "0") {

  print "<div align=\"center\" class=\"vote\"><em>Voting system is currently closed.</em></div>";

}

else {

  print "<div align=\"center\" class=\"vote\"><em>Text your votes (1-$numVotes) to <strong>(816) 533-7903</strong></em></div>";

  print "<div align=\"center\" class=\"time\">Time remaining: <strong>" .$timeleftmin . ":";

  printf("%02s",$timeleftsec);

  print "</strong></div>\n"; 

}



?>



<?php // <br /><div align="center"><u>Current Rankings</u></div>  ?>

<br />

<div align="center">

<?php



// Build up labels for graph

$valString = "";

$titleString = "";



for($i = 1; $i <= $numVotes; $i++) {

  $valString .= $vote[$i];

  $titleString .= $img[$i] . "<br />Text <strong>$i</strong> for<br />" . $name[$i];

  if($i != $numVotes) {

    $valString .= ",";

    $titleString .= ",";

  } 

} 

$graph = new BAR_GRAPH("vBar");

$graph->values = $valString;



$graph->labels = $titleString;

$graph->labelFont = "Calibri, Arial Black, Arial, Helvetica";

$graph->labelSize = 20;

$graph->labelBGColor = "#eeeeee";

$graph->labelAlign = "center";

$graph->labelBorder = "2px #000000";

$graph->labelSpace = 10;



$graph->barWidth = 50;

$graph->barColors = "#990099";

$graph->showValues = 1;       // 



$graph->absValuesBGColor = "#eeeeee";

$graph->absValuesspan = "Calibri, Arial Black, Arial, Helvetica";

$graph->absValuesSize = 20;

$graph->absValuesSuffix = " votes"; 

$graph->percValuesspan = "Calibri, Arial Black, Arial, Helvetica";

$graph->percValuesSize = 20;   

echo $graph->create();

?>







</div>

<div>&nbsp;</div>

<div align="center" class="disclaimer">Your data rates may apply. We won't invade your privacy, sell your number, or spam you.</div>





</body>

</html>