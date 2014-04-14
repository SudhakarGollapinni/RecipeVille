<?php
include "dbConnect.php";
$newName = trim ($_POST["formPersonName"]);
$newUsername = trim ($_POST["formPersonUsername"]);
$offendingChars = "\'\"!@#$%^&*();+=-\][}~|:.,{?><";

if ($newName == "" || $newUsername == "") {
	echo "<p>You must enter both a name and a username.</p>";}
else if (strpbrk ($newName, $offendingChars) || strpbrk ($newUsername, $offendingChars)) {
	echo "<p>No special characters are allowed in either a name or a username.</p>";}
else {
	$query = "insert into person values (0, '$newname', '$newUsername');";
	if (mysql_query ($query)) {
		echo "<p>Successfully inserted a person <b>$newName</b> with the username <b>$newUsername</b></p>";}
	else {
		echo "<p>There is a problem with the query: " . mysql_error() . "</p>";} ?>
