<?php

// Update these with correct values
$username = "";
$password = "";
$hostname = "";
$database = "";


$mysqli = new mysqli($hostname, $username, $password, $database);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
