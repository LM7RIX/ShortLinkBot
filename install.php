<?php
$server = "localhost";
$username = "username";
$password = "password";
$databasename = "name database";
$db = new mysqli($server, $username, $password, $databasename);
if($db == true){
  $db->query("CREATE TABLE member (id INT(100))");
  echo 'ok';
}?>
<html>
<body>
  <center>
    <h1>دیتا بیس تنظیم شد</h1>
    <p>
      فایل
      index.php
      رو با وب هوک ست کنین
    </p>
  </center>
</body>
</html>
