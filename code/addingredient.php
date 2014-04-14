<?php
include "template.php";
myHeader();
if (!isset($_SESSION['UserID'])) {
        echo '<p class="login">Please <a href="log-in.php">log in</a> to access this page.</p>';
        exit();
    }

/*$UserID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from ADMIN where UserID = '$UserID';");
$Administrator = mysql_numrows($Query);*/

print "<h2>Add Ingredient</h2>";

      /*  if (isset($_GET['Add'])) {
                $Name = htmlspecialchars(mysql_real_escape_string($_GET['Name']));
                $Unit = htmlspecialchars(mysql_real_escape_string($_GET['Unit']));
                mysql_query("insert into INGREDIENT (Name,Unit) values ('$Name', '$Unit');");    
                             
                print "<p id='alert'>The Ingredient has been added to our database <br> You will now be able to see it in the drop down list.</p>";
        }*/
        echo "<form action='addingredient.php' method='get'>
        Name: <input name='Name' type='text' /><br />";

        echo "Unit: <input name='Unit' type='text'/> <br>";        
        echo "<input name='Add' type='submit' value='Add Ingredient' />
       </form>";


 if (isset($_GET['Add'])) 
    {
       if(isset($_GET['Name']) && $_GET['Name'] != '')
       {                     
                $Name = $_GET['Name'];
                $Unit = $_GET['Unit'];
       
                mysql_query("insert into INGREDIENT (Name,Unit) values ('$Name','$Unit');");
                  echo "$mysql_query";
                print "<p id='alert'>The Ingredient has been added to our database <br> You will now be able to see it in the drop down list.</p>";
       }
       else
       {
         echo "<p id='alert'>Insufficient data to submit. At least the name of the ingredient is required.</a>";
       }
    