<?php
require_once("template.php");
myHeader();

print "<h2>Favorites</h2>";
$ViewerID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from FAVORITE join RECIPE on FAVORITE.RecipeID = RECIPE.RecipeID where FAVORITE.UserID = $ViewerID;");
$NumberOfFavorites = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfFavorites; $Index++) {
	$RecipeID = mysql_result($Query, $Index, "FAVORITE.RecipeID");
	$Photo = mysql_result($Query, $Index, "Photo");
	if ($Photo == "") {
		$Photo = "food.png";
	}
	$Name = mysql_result($Query, $Index, "Name");
	print "<a class='recipe' href='recipe.php?RecipeID=$RecipeID'><img alt='$Name' src='http://www.cs.indiana.edu/cgi-pub/ssrirang/upload/$Photo' width='128px' /><span class='recipe-name'>$Name</span></a>";
}

myFooter();
?>