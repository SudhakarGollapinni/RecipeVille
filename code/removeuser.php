<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>MCS - Remove a user</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Administration - Remove a user</h2>

<?php
  //  require_once('authorize.php');
 // require_once('connectvars.php');
include "template.php";
myHeader();
echo  "<h2> Administration - Remove a user </h2>";

  /*if (isset($_GET['id']) && isset($_GET['name']) && isset($_GET['email']))
  {
    // Grab the score data from the GET
    $id = $_GET['id'];
    $name = $_GET['name'];
    $email = $_GET['email'];

  }
  else if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email'])) {
    // Grab the score data from the POST
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email= $_POST['email'];
  }
  else {
    echo '<p class="error">Sorry, no record selected.</p>';
  }*/

  if(isset($_GET['id']))
  {
    $id = $_GET['id'];
  }

  if(isset($_POST['id']))
  {
    $id = $_POST['id'];
  }



$namequery = mysql_query("select name from USER where UserID = $id;");
$numofnames = mysql_numrows($namequery);
echo "$numofnames";
for($j=0;$j<$numofnames;$j++)
{
$name = mysql_result($namequery,$j,"name");

}

$delquery = mysql_query("DELETE FROM USER WHERE UserID = $id;");
echo " user $name successfully deleted";



  /*if (isset($_POST['submit'])) {
    if ($_POST['confirm'] == 'Yes') {
      // Connect to the database
      //$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        //or die('Error connecting to MySQL server.');

      // Delete the score data from the database
      $query = "DELETE FROM USER WHERE UserID = $id LIMIT 1;";
      mysql_query($query);


      // Confirm success with the user
      echo '<p>The user ' . $id . ' was successfully removed.</p>';
    }
    else {
      echo '<p class="error">The user was not removed.</p>';
    }
  }*/

$namequery = mysql_query("select name from USER where userid = $id;");
$emailquery = mysql_query("select email from USER where userid = $id;");

$numofnames = mysql_numrows($namequery);

for($i=0; $i<$numofnames; $i++)
{
$name = mysql_result($namequery);
$email = mysql_result($emailquery);
}


/*
  else if (isset($id)) {
    echo '<p>Are you sure you want to delete the following user</p>';
    echo '<p><strong>Name: </strong>' . $name . '<br /><strong>Email: </strong>' . $email . '</p>';
    echo '<form method="post" action="removeuser.php">';
    echo '<input type="radio" name="confirm" value="Yes" /> Yes ';
    echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br />';
    echo '<input type="submit" value="Submit" name="submit" />';
    echo '<input type="hidden" name="id" value="' . $id . '" />';
    echo '<input type="hidden" name="name" value="' . $name . '" />';
    echo '<input type="hidden" name="email" value="' . $email . '" />';
    echo '</form>';
  }*/

  echo '<p><a href="admin.php">&lt;&lt; Back to admin page</a></p>';
?>

</body> 
</html>
