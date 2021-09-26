<?php
  $root = $_SERVER["DOCUMENT_ROOT"];
  require_once "$root/jsoned/config.php";

  $dbh = new PDO(DB_URL, DB_USER_NAME, DB_USER_PASSWORD,
            [
              PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . DB_CHARSET . "'",
              PDO::MYSQL_ATTR_FOUND_ROWS => true
            ]);
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
?>