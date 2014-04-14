

<?php
    include "template.php";
myHeader();
echo '<h2> User Administration</h2>';
    /*require_once('authorize.php');
    require_once('appvars.php');
    require_once('connectvars.php');*/
    
  // Connect to the database 


  // Retrieve the score data from MySQL
  $query = "SELECT * FROM USER;";

  $data = mysql_query($query);

  $numusers = mysql_numrows($data);

for($i=0; $i< $numusers; $i++)
{
$username = mysql_result($data,$i, "Name");
$userID = mysql_result($data,$i, "UserID");
echo '<table>';
echo "<tr> <td> $username  <a href='removeuser.php?id=$userID'> Remove $Username</a></td></tr> <br>";
echo '</table>';

}
  // Loop through the array of score data, formatting it as HTML 
  /*echo '<table>';
  while ($row = mysqli_fetch_array($data)) { 
    // Display the score data
    echo '<tr class="scorerow"><td><strong>' . $row['Name'] . '</strong></td>';
    echo '<td>' . $row['Email'] . '</td>';
      echo '<td><a href="removeuser.php?id=' . $row['UserID'] .       '&amp;name=' . $row['Name'] . '&amp;email=' . $row['Email'] .'">Remove</a></td></tr>';
  }
  echo '</table>';*/

 // mysqli_close($dbc);
?>

</body> 
</html>
