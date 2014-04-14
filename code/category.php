<?php
require_once("template.php");
myHeader();

$UserID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from ADMIN where UserID = '$UserID';");
$Administrator = mysql_numrows($Query);

$CategoryID = htmlspecialchars(mysql_real_escape_string($_GET["CategoryID"]));
$Query = mysql_query("select * from CATEGORY where CategoryID = '$CategoryID';");
$CATEGORYName = mysql_result($Query, 0, "CATEGORY.Name");

if ($Administrator == 1) {
	if (isset($_POST["Rename"])) {
		$CATEGORYName = htmlspecialchars(mysql_real_escape_string($_POST["CATEGORYName"]));
		mysql_query("update CATEGORY set Name = '$CATEGORYName' where CategoryID = '$CategoryID';");
	} else if (isset($_POST["Delete"])) {
		mysql_query("delete from CATEGORY where CategoryID = '$CategoryID';");
		mysql_query("delete from RECIPE_CATEGORY where CategoryID = '$CategoryID';");
	}
	if (!isset($_POST["Delete"])) {
		print "<h2><form action='category.php?CategoryID=$CategoryID' method='post'>
		<input name='CATEGORYName' type='text' value='$CATEGORYName' />
		<input name='Rename' type='submit' value='Rename Category' />
		<input name='Delete' type='submit' value='Delete Category' />
		</form></h2>";
	}
	if (isset($_POST["Rename"])) {
		print "<p id='alert'>This category is renamed.</p>";
	} else if (isset($_POST["Delete"])) {
		print "<p id='alert'>This category is deleted.<br /><a href='index.php'>Home</a></p>";
	}
} else {
	print "<h2>$CATEGORYName</h2>";
}

$Query = mysql_query("select * from RECIPE_CATEGORY join RECIPE on RECIPE_CATEGORY.RecipeID = RECIPE.RecipeID where RECIPE_CATEGORY.CategoryID = '$CategoryID';");
$NumberOfRecipes = mysql_numrows($Query);
if ($NumberOfRecipes == 0) {
	print "<p id='alert'>This category is empty.</p>";
}
for ($Index = 0; $Index < $NumberOfRecipes; $Index++) {
	$RECIPEName = mysql_result($Query, $Index, "RECIPE.Name");
	$Photo = mysql_result($Query, $Index, "Photo");
	if ($Photo == "") {
		$Photo = "food.png";
	}
	$RecipeID = mysql_result($Query, $Index, "RecipeID");
	print "<p class='recipe'><img alt='$RECIPEName' src='http://www.cs.indiana.edu/cgi-pub/ssrirang/upload/$Photo' width='128px' />
	<a class='recipe-name' href='recipe.php?RecipeID=$RecipeID'>$RECIPEName</a></p>";
}

myFooter();
?>