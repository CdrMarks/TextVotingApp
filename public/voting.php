<?php

// Remove comments when voting is over

// smsVotingOver();



$numVotes = 7;
$name[1] = "1";
$name[2] = "2";
$name[3] = "3";
$name[4] = "4";
$name[5] = "5";
$name[6] = "6";
$name[7] = "7";

// Response functions
function smsHeader() {
  header("content-type: text/xml");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
}

function smsVotingOver() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>The voting time is over, sorry!</Sms>\n";
  print "</Response>\n";
}
function smsNAN() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>That's not a numeric vote, try again!</Sms>\n";
  print "</Response>\n";
}

function smsErrorBadVote() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>That was not a valid vote, try again!</Sms>\n";
  print "</Response>\n";
}

function smsDuplicateVote($theVote) {
  smsHeader();
  print "<Response>\n";
  print "<Sms>Since you already voted, we updated your vote to " . $theVote . ".</Sms>\n";
  print "</Response>\n";
}

function smsErrorTooManyRows() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>Error: DB pulled too many rows!</Sms>\n";
  print "</Response>\n";
}

function smsErrorAlreadyVoted() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>You already voted, cheater!</Sms>\n";
  print "</Response>\n";
}

function smsThanks($theVote) {
  smsHeader();
  //$v = intval($v);
  print "<Response>\n";
  print "<Sms>Your vote for " . $theVote . " was recorded. SWE would like to thank you for coming to Mr. Engineer!</Sms>\n";
  print "</Response>\n";
}


// Connect to database
$mysqli = new mysqli('localhost', 'username', 'password', 'database');
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

// Get info from Twilio
$phone = $_REQUEST['From'];
$vote = $_REQUEST['Body'];

// DEBUG:
/*
print "phone: $phone<br />";
print "vote: $vote<br />";
*/

// Vote should only be one number, keep only one number
if(!is_numeric($vote)) {
  smsNAN();
  die();
}

// Vote should only be one number, keep only one number
if(intval($vote) < 1 || intval($vote) > $numVotes) {
  smsErrorBadVote();
  die();
}

// PHP/MySQL security
$vote = $mysqli->real_escape_string($vote);

// Performing SQL query
$query = "SELECT * FROM vote WHERE phone LIKE '$phone'";
$result = $mysqli->query($query);

// If results >1, we have other problems
if($result->num_rows > 1) {
  smsErrorTooManyRows();
  die();
}

// If results =1, send error
if($result->num_rows == 1) {
  $query2 = "UPDATE vote SET vote = '" . $vote . "' WHERE phone = '" . $phone . "';";
  $result2 = $mysqli->query($query2);
//  smsErrorAlreadyVoted();
  smsDuplicateVote($name[$vote]);

  die();
}

// If results = 0, insert vote
// In theory, only 0 rows left...
//if($result->num_rows == 0) {
  $query2 = "INSERT INTO vote (phone, vote) VALUES ('" . $phone . "','" . $vote . "');";
  $result2 = $mysqli->query($query2);
  smsThanks($name[$vote]);
//}

// Free resultset
//mysql_free_result($result2);
$result->close();

// Closing connection
$mysqli->close();


