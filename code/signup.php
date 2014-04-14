<?php
require_once("template.php");
myHeader();

  $ViewerID = htmlspecialchars(mysql_real_escape_string($_SESSION["UserID"]));
  $Query = mysql_query("select * from USER where UserID = '$ViewerID';");
  $Viewer = mysql_result($Query, 0, "USER.Name");

  print "<h2>Register</h2>";
  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $username = htmlspecialchars(mysql_real_escape_string(trim($_POST['username'])));
    $email = htmlspecialchars(mysql_real_escape_string(trim($_POST['email'])));
    $password1 = htmlspecialchars(mysql_real_escape_string(trim($_POST['password1'])));
    $password2 = htmlspecialchars(mysql_real_escape_string(trim($_POST['password2'])));

    if (!(strpos($email, '@') !== FALSE)) {
      echo '<p id="alert">Your email address is not valid.</p>';
        $username = "";
    } else if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      // Make sure someone isn't already registered using this username
      $query = "SELECT * FROM USER WHERE Name = '$username' or Email='$email' ";
      $data = mysql_query($query);
      if (mysql_num_rows($data) == 0) {
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO USER (Name, PasswordHash, Email, JoinDate) VALUES ('$username', SHA('$password1'), '$email', NOW())";
        mysql_query($query);

        // Confirm success with the user
        echo '<p>Your new account has been successfully created. You\'re now ready to <a href="log-in.php">log in</a>.</p>';

        mysql_close($dbc);
        exit();
      }
      else {
        // An account already exists for this username, so display an error message
        echo '<p id="alert">An account or email already exists for this username. Please use a different address.</p>';
        $username = "";
      }
    }
    else {
      echo '<p id="alert">You must enter all of the sign-up data, including the desired password twice.</p>';
    }
  }

  mysql_close($dbc);
?>

  <p style='clear: left'>Please enter your username and desired password to sign up to Recipeville.</p>
  <form action="signup.php" method="post">
    <fieldset>
      <legend>Registration Info</legend>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $Email; ?>" /><br />
      <label for="password1">Password:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">Password (retype):</label>
      <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="Sign Up" name="submit" />
  </form>
</div>
</body> 
</html>
<?php
myFooter();
?>