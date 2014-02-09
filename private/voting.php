<?php
// Response functions
function smsHeader() {
  header("content-type: text/xml");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
}

function smsVotingOver() {
  smsHeader();
  print "<Response>\n";
  print "<Sms>The Mr. Engineer voting system isn't currently open. Sorry!</Sms>\n";
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


// Remove comments when voting is over

// smsVotingOver();

// Connect to database
$mysqli = new mysqli('localhost', 'table', 'password', 'database');
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SELECT value FROM setting WHERE setting = 'enabled'";
$result = $mysqli->query($query);
$row = $result->fetch_array(MYSQLI_NUM);
$enabled = $row[0];

// Not enabled? Immediately send invalid response
if($enabled == "0") {
  smsVotingOver();
  die();
}


$numVotes = 7;
$name[1] = "name";
$name[2] = "name";
$name[3] = "name";
$name[4] = "name";
$name[5] = "name";
$name[6] = "name";
$name[7] = "name";


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
$query = "SELECT * FROM votes WHERE phone LIKE '$phone'";
$result = $mysqli->query($query);

// If results >1, we have other problems
if($result->num_rows > 1) {
  smsErrorTooManyRows();
  die();
}

// If results =1, send error
if($result->num_rows == 1) {
  $query2 = "UPDATE votes SET vote = '" . $vote . "' WHERE phone = '" . $phone . "';";
  $result2 = $mysqli->query($query2);
//  smsErrorAlreadyVoted();
  smsDuplicateVote($name[$vote]);

  die();
}

// If results = 0, insert vote
// In theory, only 0 rows left...
//if($result->num_rows == 0) {
  $query2 = "INSERT INTO votes (phone, vote) VALUES ('" . $phone . "','" . $vote . "');";
  $result2 = $mysqli->query($query2);
  smsThanks($name[$vote]);
//}

// Free resultset
//mysql_free_result($result2);
$result->close();

// Closing connection
$mysqli->close();