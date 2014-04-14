<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Make Me Elvis - Send Email</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php
    require_once('authorize.php');
    require_once('connectvars.php');

  if (isset($_POST['submit'])) {
    $from = 'elmer@makemeelvis.com';
    $subject = $_POST['subject'];
    $text = $_POST['elvismail'];
    $output_form = false;

    if (empty($subject) && empty($text)) {
      // We know both $subject AND $text are blank 
      echo 'You forgot the email subject and body text.<br />';
      $output_form = true;
    }

    if (empty($subject) && (!empty($text))) {
      echo 'You forgot the email subject.<br />';
      $output_form = true;
    }

    if ((!empty($subject)) && empty($text)) {
      echo 'You forgot the email body text.<br />';
      $output_form = true;
    }
  }
  else {
    $output_form = true;
  }

  if ((!empty($subject)) && (!empty($text))) {
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
      or die('Error connecting to MySQL server.');

    $query = "SELECT * FROM USER";
    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

    while ($row = mysqli_fetch_array($result)){
      $to = $row['Email'];
      $name = $row['Name'];
      $msg = "Dear $name,\n$text";
      mail($to, $subject, $msg, 'From:' . $from);
      echo 'Email sent to: ' . $to . '<br />';
    } 

    mysqli_close($dbc);
  }

  if ($output_form) {
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="subject">Subject of email:</label><br />
    <input id="subject" name="subject" type="text" value="<?php echo $subject; ?>" size="30" /><br />
    <label for="elvismail">Body of email:</label><br />
    <textarea id="elvismail" name="elvismail" rows="8" cols="40"><?php echo $text; ?></textarea><br />
    <input type="submit" name="submit" value="Submit" />
  </form>

<?php
  }
?>

</body>
</html>
