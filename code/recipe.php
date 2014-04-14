<?php
require_once("template.php");
myHeader();

$ViewerID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from USER where UserID = '$ViewerID';");
$Viewer = mysql_result($Query, 0, "USER.Name");

$RecipeID = htmlspecialchars(mysql_real_escape_string($_GET["RecipeID"]));
$Query = mysql_query("select *, datediff(now(), Date) as SubmissionDate from RECIPE left join FAVORITE on RECIPE.RecipeID = FAVORITE.RecipeID and FAVORITE.UserID = '$ViewerID' join USER on RECIPE.UserID = USER.UserID where RECIPE.RecipeID = '$RecipeID';");
$Favorite = mysql_result($Query, 0, "FAVORITE.RecipeID");
$Description = mysql_result($Query, 0, "Description");
$SubmissionDate = mysql_result($Query, 0, "SubmissionDate");
$Difficulty = mysql_result($Query, 0, "Difficulty");
$Directions = "<li>" . str_replace("
", "</li><li>", mysql_result($Query, 0, "Directions")) . "</li>";
$LinkToOriginal = mysql_result($Query, 0, "LinkToOriginal");
$LinkToVideo = mysql_result($Query, 0, "LinkToVideo");
$Photo = mysql_result($Query, 0, "Photo");
if ($Photo == "") {
	$Photo = "food.png";
}
$RECIPEName = mysql_result($Query, 0, "RECIPE.Name");
$Tags = mysql_result($Query, 0, "Tags");
$TimeRequired = mysql_result($Query, 0, "TimeRequired");
$Submitter = mysql_result($Query, 0, "USER.Name");
$SubmitterID = mysql_result($Query, 0, "RECIPE.UserID");

$ViewerID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
$Query = mysql_query("select * from USER where UserID = '$ViewerID';");
$Viewer = mysql_result($Query, 0, "USER.Name");

if (isset($_POST["Favorite"])) {
	mysql_query("insert into FAVORITE (UserID, RecipeID) values ('$ViewerID', '$RecipeID');");
	print "<p id='alert'>You added this recipe to your favorites.</p>";
	$Favorite = $RecipeID;
} else if (isset($_POST["Unfavorite"])) {
	mysql_query("delete from FAVORITE where UserID = '$ViewerID' and RecipeID = '$RecipeID';");
	print "<p id='alert'>You removed this recipe from your favorites.</p>";
	$Favorite = "";
} else if (isset($_POST["Delete"])) {
	mysql_query("delete from FAVORITE where RecipeID = '$RecipeID';");
	mysql_query("delete from RECIPE where RecipeID = '$RecipeID';");
	mysql_query("delete from RECIPE_CATEGORY where RecipeID = '$RecipeID';");
	mysql_query("delete from RECIPE_INGREDIENT where RecipeID = '$RecipeID';");
	mysql_query("delete from REVIEW where RecipeID = '$RecipeID';");
	print "<p id='alert'>This recipe is deleted.</p>";
} else if (isset($_POST["Rating"])) {
	$Text = htmlspecialchars(mysql_real_escape_string($_POST["Text"]));
	$Rating = htmlspecialchars(mysql_real_escape_string($_POST["Rating"]));
	$Query = mysql_query("select * from REVIEW where RecipeID = '$RecipeID' and UserID = '$ViewerID';");
	$NumberOfReviews = mysql_numrows($Query);
	if ($NumberOfReviews == 0) {
		mysql_query("insert into REVIEW (RecipeID, UserID, Text, Rating, Date) values ('$RecipeID', '$ViewerID', '$Text', '$Rating', now());");
	} else {
		mysql_query("update REVIEW set Text = '$Text', Rating = '$Rating', Date = now() where RecipeID = '$RecipeID' and UserID = '$ViewerID';");
	}
	print "<p id='alert'>Your review has been posted.</p>";
}

if (!isset($_POST["Delete"])) {
print "<h2>$RECIPEName</h2>
<img alt='$RECIPEName' height='128' id='photo' src='http://www.cs.indiana.edu/cgi-pub/ssrirang/upload/$Photo' />";

if ($Viewer) {
	print "<form action='recipe.php?RecipeID=$RecipeID' method='post'>";
	if ($Favorite == $RecipeID) {
		print "<input name='Unfavorite' type='submit' value='Remove From Favorites' />";
	} else {
		print "<input name='Favorite' type='submit' value='Add to Favorites' />";
	}
	print "</form>";
}

$Query = mysql_query("select * from ADMIN where UserID = '$ViewerID';");
$Administrator = mysql_numrows($Query);
if ($Administrator == 1 || $SubmitterID == $ViewerID) {
	print "<form action='recipe.php?RecipeID=$RecipeID' method='post'>";
	print "<input name='Delete' type='submit' value='Delete Recipe' />";
	print "</form>";
}

print "<p>$Description</p>";

print "<h3>Ingredients</h3><ul>";
$Query = mysql_query("select * from RECIPE_INGREDIENT join INGREDIENT on RECIPE_INGREDIENT.IngredientID = INGREDIENT.IngredientID where RecipeID = '$RecipeID';");
$NumberOfIngredients = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfIngredients; $Index++) {
	$Amount = mysql_result($Query, $Index, "Amount");
	$Unit = mysql_result($Query, $Index, "Unit");
	$INGREDIENTName = mysql_result($Query, $Index, "Name");
	print "<li>";
	 if ($Amount != 0) {
                if($Unit == 'cups')
                {
                  $mamount = round($Amount * 236.58824);
                  $munit = 'g';

                  $lamount = round($Amount * 236.58824);
                  $lunit = 'mL';
                  print "$Amount $Unit ($mamount $munit/$lamount $lunit) ";
                }
                if($Unit == 'teaspoons')
                {
                  $mamount = round($Amount * 5);
                  $munit = 'g';

                  $lamount = round($Amount * 5);
                  $lunit = 'mL';
                  print "$Amount $Unit ($mamount $munit/$lamount $lunit) ";
                }
                if($Unit == 'tablespoons')
                {
                  $mamount = round($Amount * 15);
                  $munit = 'g';

                  $lamount = round($Amount * 15);
                  $lunit = 'mL';
                  print "$Amount $Unit ($mamount $munit/$lamount $lunit) ";
                }
               // print "$Amount $Unit ";
        }

	print strtolower($INGREDIENTName);
	if ($Amount == 0) {
		print " to taste";
	}
	print "</li>";
}
print "</ul>";

print "<h3>Method</h3>
<ol>$Directions</ol>";
if ($LinkToVideo) {
	print "<iframe width='560' height='315' src='$LinkToVideo' frameborder='0' allowfullscreen></iframe>";
}

print "<p>Categories: ";
$Query = mysql_query("select * from CATEGORY join RECIPE_CATEGORY on CATEGORY.CategoryID = RECIPE_CATEGORY.CategoryID where RecipeID = '$RecipeID';");
$NumberOfCategories = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfCategories; $Index++) {
	$CategoryID = mysql_result($Query, $Index, "CategoryID");
	$CATEGORYName = mysql_result($Query, $Index, "Name");
	print "<a href='category.php?CategoryID=$CategoryID'>$CATEGORYName</a> ";
}

print "<br />
Date: ";
if ($SubmissionDate == 0) {
	print "Today";
} else {
	print "$SubmissionDate days ago";
}
print "<br />
Difficulty: $Difficulty<br />
Time: $TimeRequired minutes<br />
Tags: $Tags<br />
Submitter: $Submitter<br />
<a href='$LinkToOriginal'>Source</a></p>";

print "<h3>Reviews</h3>";
$Query = mysql_query("select *, datediff(now(), Date) as ReviewDate from REVIEW join USER on REVIEW.UserID = USER.UserID where RecipeID = '$RecipeID';");
$NumberOfReviews = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfReviews; $Index++) {
	$Reviewer = mysql_result($Query, $Index, "USER.Name");
	$Text = mysql_result($Query, $Index, "Text");
	$Rating = mysql_result($Query, $Index, "Rating");
	$ReviewDate = mysql_result($Query, $Index, "ReviewDate");
	print "<div class='review'><h4>$Reviewer</h4> 
	<span class='date'>";
	if ($ReviewDate == 0) {
		print "Today";
	} else {
		print "$ReviewDate days ago";
	}
	print "</span> ";
	for ($Star = 0; $Star < $Rating; $Star++) {
		print " &#9733;";
	}
	for ($Star = 0; $Star < 5 - $Rating; $Star++) {
		print " &#9734;";
	}
	print "<p>$Text</p></div>";
}

if ($Viewer) {
	print "<div class='review'><h4>$Viewer</h4> 
	<span class='date'>Today</span>
	<form action='recipe.php?RecipeID=$RecipeID' method='post'>
	<select name='Rating'>
	<option value='5'>&#9733; &#9733; &#9733; &#9733; &#9733;</option>
	<option value='4'>&#9733; &#9733; &#9733; &#9733; &#9734;</option>
	<option value='3'>&#9733; &#9733; &#9733; &#9734; &#9734;</option>
	<option value='2'>&#9733; &#9733; &#9734; &#9734; &#9734;</option>
	<option value='1'>&#9733; &#9734; &#9734; &#9734; &#9734;</option></select>
	<textarea cols='' name='Text' rows=''></textarea>
	<input type='submit' value='Submit' /></form></div>";
}
}
myFooter();
?>
