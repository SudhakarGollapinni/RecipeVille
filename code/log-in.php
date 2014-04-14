<?php
mysql_connect("silo.cs.indiana.edu", "b561f13_ssrirang", "qwerty13") or die ("mysql_connect failed");
mysql_select_db("b561f13_ssrirang") or die ("mysql_select_db failed");
session_start();
if (isset($_POST["LogIn"])) {
    $Email = trim(htmlspecialchars(mysql_real_escape_string($_POST['Email'])));
    $Password = trim(htmlspecialchars(mysql_real_escape_string($_POST['Password'])));
    $Query = mysql_query("select * from USER where Email = '$Email' and PasswordHash = SHA('$Password')");
    $UserID = mysql_result($Query, 0, "UserID");
    if ($UserID) {
        $_SESSION["UserID"] = $UserID;
    } else {
        $Alert = "Your email address and password are incorrect.";
    }
} else if (isset($_POST["LogOut"]) or !isset($_SESSION["UserID"])) {
    $_SESSION["UserID"] = null;
    $Alert = "You are logged out.";
} else {
    $UserID = $_SESSION["UserID"];
    $Query = mysql_query("select * from USER where UserID = $UserID;");
}
if ($UserID) {
    $Name = mysql_result($Query, 0, "Name");   
    $Alert = "You are logged in as $Name.<br />
    <a href='index.php'>Home</a>";
}

require_once("template.php");
myHeader();
print "<h2>Log In</h2>";
if ($Alert) {
    print "<p id='alert'>$Alert</p>";
}
if (!$UserID) {
    print "<form action='log-in.php' method='post'>
    Email: <input name='Email' type='text'><br />
    Password: <input name='Password' type='password' /><br />
    <input name='LogIn' type='submit' value='Log In' />
    </form>";
}
myFooter();
?>