<?php
require_once("template.php");
myHeader();

    require_once('connectvars.php');
    // Make sure the user is logged in before going any further.
    if (!isset($_SESSION['UserID'])) {
        echo '<p class="login">Please <a href="log-in.php">log in</a> to access this page.</p>';
        exit();
    }
    else {
        print "<p />";
    }
    
    // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (isset($_POST['submit']))
    {
        
        $rname = mysqli_real_escape_string($dbc, trim($_POST['rname']));
        $iname = mysqli_real_escape_string($dbc, trim($_POST['iname']));
        $iamount = mysqli_real_escape_string($dbc, trim($_POST['iamount']));
        $iunit = mysqli_real_escape_string($dbc, trim($_POST['iunit']));
        $methods = mysqli_real_escape_string($dbc, trim($_POST['Methods']));
        $selectOption = mysqli_real_escape_string($dbc, trim($_POST['category']));
        $diffculty=mysqli_real_escape_string($dbc, trim($_POST['diffculty']));
        $time=mysqli_real_escape_string($dbc, trim($_POST['time']));
        $source=mysqli_real_escape_string($dbc, trim($_POST['source']));
        $tags=mysqli_real_escape_string($dbc, trim($_POST['tags']));
        $uid=$_SESSION['UserID'];
        
        $pic_path;
        
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if ((($_FILES["file"]["type"] == "image/gif")
             || ($_FILES["file"]["type"] == "image/jpeg")
             || ($_FILES["file"]["type"] == "image/jpg")
             || ($_FILES["file"]["type"] == "image/pjpeg")
             || ($_FILES["file"]["type"] == "image/png"))
            && in_array($extension, $allowedExts))
        {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            }
            else
            {
                $photo_name = mysqli_real_escape_string($dbc, trim($_FILES["file"]["name"]));
                date_default_timezone_set("UTC");
                $photo_name =date('Y-m-d H:i:s') . "_" . $photo_name;
                echo "Upload: " . $photo_name . "<br>";
                echo "Type: " . $_FILES["file"]["type"] . "<br>";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

                
                if (file_exists("upload/" . $photo_name))
                {
                    echo $photo_name . " already exists. ";
                }
                else
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"],
                                       "upload/" . $photo_name);
                    $pic_path=$photo_name;
                    echo "Stored in: " . $pic_path;
                }
            }
        } else if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $error = 1;
            echo "<p id='alert'>The photo that you uploaded is not valid. Please try uploading a different photo.</p>";
        }

        
        
        
        //echo $rname.' '.$ingredients.' '.$methods.' '.$selectOption.' '.$diffculty.' '.$time.' '.$source.' ';

        if(!empty($rname) && !empty($time) && !empty($methods))
        {
            if(!empty($pic_path))
            {
              $query = "INSERT INTO RECIPE (Name, TimeRequired, Difficulty, UserID, Directions, Tags, Date, Description, Photo) VALUES ('$rname', '$time', '$diffculty', '$uid', '$methods', '$tags', NOW(), '$ingredients', '$pic_path')";
            } else {
              $query = "INSERT INTO RECIPE (Name, TimeRequired, Difficulty, UserID, Directions, Tags, Date, Description) VALUES ('$rname', '$time', '$diffculty', '$uid', '$methods', '$tags', NOW(), '$ingredients')";
            }
            
            mysqli_query($dbc, $query) or die('An Error occurs');
            $ridquery = "select RecipeID from RECIPE where name = '$rname' order by RecipeID desc limit 1";// and TimeRequired = '$time' and Difficulty = '$difficulty' and UserID = '$uid' and Directions = '$methods' and Tags = '$tags'";
            $ridresult = mysql_query($ridquery);
            $recipeid = mysql_result($ridresult, 0, "RecipeID");
            print "<p id='alert'>You have uploaded a new recipe. Thanks!<br />
            <a href='recipe.php?RecipeID=$recipeid'>$rname</a></p>";
            $numrids = mysql_numrows($ridresult);
            for($j=0;$j<$numrids;$j++)
            {
             $recipeid=mysql_result($ridresult,$j);
             print "";
             if(!empty($iname) && !empty($iamount))
                {
                  $iquery = "select IngredientID from INGREDIENT where name = '$iname';";
                  echo "$iquery";
                  $iresult = mysql_query($iquery);
                  $inum = mysql_numrows($iresult);
                  for($i=0; $i<$inum; $i++)
                  {
                    $ingredientid=mysql_result($iresult,$i);
                    $insertquery = "INSERT INTO RECIPE_INGREDIENT VALUES ($recipeid, $ingredientid, $iamount);";
                    $insertresult = mysql_query($insertquery);
                    //echo '$insert';

                  }
                  /* if(empty($iresult))
                  {$newinginsertquery = "INSERT INTO INGREDIENT (Name, Unit) VALUES ('$iname','$iunit'); } */
                  }
            }

            // insert ingredients here
            $Index = 1;
            while (isset($_POST["p_scnt_$Index"])) {
                $Amount = htmlspecialchars(mysql_real_escape_string($_POST["p_scnt_$Index"]));
                $IngredientID = htmlspecialchars(mysql_real_escape_string($_POST["IngredientID_$Index"]));
                mysql_query("insert into RECIPE_INGREDIENT (RecipeID, IngredientID, Amount) values ($recipeid, $IngredientID, $Amount);");
                $Index++;
            }
			
            mysqli_close($dbc);
            exit();
        }
        else{
            $error_msg = "<p id='alert'>Sorry, you must enter all required fields.</p>";
        }      
        
        
    }
    
    mysqli_close($dbc);
    ?>

<form enctype="multipart/form-data" method="post" action="add-recipe.php">
<fieldset>
<legend>Recipe Info</legend>
<label for="rname">Recipe Name*:</label>
<input type="text" size="32" id="rname" name="rname" value="<?php if (!empty($rname)) echo $rname; ?>" /><br />
<label for="file">Photo:</label>
<input type="file" name="file" id="file"><br>
Ingredients:
<!--
  Name:<input type="text" name="iname">
  Amount:<input type="text" name="iamount"><br>
  Unit:<input type="text" name="iunit"><br>
-->

<?php
$IngredientMenu = "";
$IngredientMenu .= '<select name="IngredientID_\' + i + \'">';
$Query = mysql_query("select * from INGREDIENT order by Name;");
$NumberOfIngredients = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfIngredients; $Index++) {
    $IngredientID = mysql_result($Query, $Index, "IngredientID");
    $Name = htmlspecialchars(mysql_real_escape_string(mysql_result($Query, $Index, "Name")));
    $Unit = htmlspecialchars(mysql_real_escape_string(mysql_result($Query, $Index, "Unit")));
    $IngredientMenu .= '<option value="' . $IngredientID . '">' . $Name;
    if ($Unit != "") {
        $IngredientMenu .= ' (' . $Unit . ')';
    }
    $IngredientMenu .= '</option>';
}
$IngredientMenu .= "</select>";
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script>
$(function() {
    var scntDiv = $('#p_scents');
    var i = $('#p_scents p').size() + 1;
    
    $('#addScnt').on('click', function() {
        $('<p><input type="text" id="p_scnt" size="20" name="p_scnt_' + i +'" value="" placeholder="Amount" /><?php print $IngredientMenu ?><a href="#" class="remScnt">X</a></p>').appendTo(scntDiv);
        i++;

        $('.remScnt').on('click', function() {
            $(this).parents('p').remove();
            i--;
            return false;
        });

        return false;
    });   
});
</script>

<br/><a href="#" id="addScnt">Add Ingredient</a>
<div id="p_scents">
</div>

<a href="addingredient.php">Add Other Ingredient</a><br>

Method:<br />
<textarea name="Methods" rows="10"></textarea>
Category:
<?php
    require_once('connectvars.php');
    
    echo '<select name="category">';
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
    
    $query = "SELECT * FROM CATEGORY where SuperCategoryID is not null order by Name;";
    $result = mysqli_query($dbc, $query) or die('Error connecting to MySQL server.');
    
    while($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row['CategoryID'] . "'>" . $row['Name'] . "</option>";
        
    }
    mysqli_close($dbc);
    
    echo '</select><br /><br />';
    ?>
Difficulty: <input type="radio" name="diffculty" value="easy" /> Easy <input type="radio" name="diffculty" value="medium" /> Medium <input type="radio" name="diffculty" value="hard" /> Hard<br />
<br />
Time*: <input size="8" type="text" name="time" /> minutes<br />
Tags: <input size="8" type="text" name="tags" /><br />
Source: <input size="64" type="text" name="source"/><br />

</fieldset>
<input type="submit" value="Add it!" name="submit" />

</form>

<?php
myF