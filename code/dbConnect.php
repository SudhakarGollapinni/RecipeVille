<?php
$dbHost = "silo.cs.indiana.edu";
#$dbUserAndName = "b561f13_schackb";
$dbUserAndName = "b561f13_ssrirang";
#$dbPass = "29[(3\$Ckk9)Y";
$dbPass = "qwerty13";

$_TABLE_NAME = "person";
$_PERSON_ID_FIELD = "personID";
$_PERSON_NAME_FIELD = "name";
$_PERSON_USERNAME_FIELD = "username";

mysql_connect ($dbHost, $dbUserAndName, $dbPass) or die ("Cannot connect to host $dbHost with user $dbUserAndName and the password provided.");
mysql_select_db ($dbUserAndName) or die ("Database $dbUserAndName not found on host $dbHost"); ?>