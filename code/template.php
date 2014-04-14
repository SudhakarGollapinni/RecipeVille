<?php
function myHeader() {
	print "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
	'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml' lang='en' xml:lang='en'>
	<head><title>Recipeville</title>
	<link href='style.css' rel='stylesheet' type='text/css' />
	</head>
	<body>";

	mysql_connect("silo.cs.indiana.edu", "b561f13_ssrirang", "qwerty13") or die ("mysql_connect failed");
	mysql_select_db("b561f13_ssrirang") or die ("mysql_select_db failed");
	session_start();
	$UserID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
	$Query = mysql_query("select * from USER left join ADMIN on USER.UserID = ADMIN.UserID where USER.UserID = '$UserID';");
	$Name = mysql_result($Query, 0, "Name");
	$Administrator = mysql_result($Query, 0, "ADMIN.UserID");

	print "<div id='header'>
	<h1><img alt='Recipeville' height='64' id='logo' src='logo.png' />Recipeville</h1>
	<div id='menu'>
	<a class='menu-item' href='index.php'>Home</a>
	<a class='menu-item' href='search.php'>Search</a>";
	if ($Administrator) {
		print "<a class='menu-item' href='add-category.php'>Add Category</a>";
		print "<a class='menu-item' href='admin.php'>Manage Users</a>";
	}
	if ($UserID) {
		print "<a class='menu-item' href='add-recipe.php'>Add Recipe</a>
		<a class='menu-item' href='favorites.php'>Favorites</a>
		<form action='log-in.php' class='menu-item' method='post'>
		<input name='LogOut' type='submit' value='Log Out' />
		</form>";
	} else {
		print "<a class='menu-item' href='signup.php'>Register</a>
		<a class='menu-item' href='log-in.php'>Log In</a>";
	}
	print "</div></div>
	<div id='main'>";
}
function myFooter() {
	print "</div></body></html>";
}
?>