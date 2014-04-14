showPeople.php<br />
<?php
echo "Breakpoint 1<br />";
include "dbConnect.php";

$query = "select * from $_TABLE_NAME";
$result = mysql_query ($query);
$num = mysql_numrows ($result);

echo "<table border = '1'><tr><th>Person ID</th><th>Name</th><th>Username</th>";

for ($i = 0; $i < $num; $i++) {
	echo "<tr>";
	$personID = mysql_result ($result, $i, $_PERSON_ID_FIELD);
	$personName = mysql_result ($result, $i, $_PERSON_NAME_FIELD);
	$personUsername = mysql_result ($result, $i, $_PERSON_USERNAME_FIELD);
	echo "<td>$personID</td><td>$personName</td><td>$personUsername</td></tr>";}

echo "</table>"; ?>

<p>Add a person to the database:</p>
<form action = 'addPerson.php' method = 'post'>
