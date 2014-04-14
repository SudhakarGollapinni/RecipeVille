<?php
require_once("template.php");
myHeader();

$UserID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from ADMIN where UserID = '$UserID';");
$Administrator = mysql_numrows($Query);

print "<h2>Add Category</h2>";
if ($Administrator == 1) {
	if (isset($_POST["Add"])) {
		$Name = htmlspecialchars(mysql_real_escape_string($_POST["Name"]));
		$SuperCategoryID = htmlspecialchars(mysql_real_escape_string($_POST["SuperCategoryID"]));
		mysql_query("insert into CATEGORY (Name, SuperCategoryID) values ('$Name', '$SuperCategoryID');");
		print "<p id='alert'>The category is added.</p>";
	}
	print "<form action='add-category.php' method='post'>
	Name: <input name='Name' type='text' /><br />";
	
	print "Supercategory:
	<select name='SuperCategoryID'>";    
    $Query = mysql_query("select * from CATEGORY where SuperCategoryID is null;");
    $NumberOfSuperCategories = mysql_numrows($Query);
    for ($Index = 0; $Index < $NumberOfSuperCategories; $Index++) {
    	$SuperCategoryID = mysql_result($Query, $Index, "CategoryID");
    	$Name = mysql_result($Query, $Index, "Name");
        print "<option value='$SuperCategoryID'>$Name</option>";
    }
    print "</select><br />";

	print "<input name='Add' type='submit' value='Add Category' />
	</form>";
} else {
	print "<p id='alert'>Adding a category is restricted to administrators.</p>";
}

myFooter();
?>