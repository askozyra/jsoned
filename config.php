<?php
  // title of the site
  define("TIT1", "jsoned");

  // name of the project folder
  define("ROOT_NAME", "jsoned");

  // name of the domain
  define("DOMAIN_NAME", "localhost");

  // secured or not secured
  define("TRANSFER_PROTOCOL", "http");

  // absolute path to root
  define("ABSOLUTE_PATH", $_SERVER["DOCUMENT_ROOT"] . "/" . TIT1);

  // folder that contains the site (leave empty for the root)
  define("RELATIVE_PATH", "");

  // login of the user of the DB
  define("DB_USER_NAME", "root");
  
  // password of the user of the DB
  define("DB_USER_PASSWORD", "");
  
  // connection parameters to DB
  define("DB_URL", "mysql:dbname=json_db;host=localhost");

  // prefix of site tables in DB
  define("DB_PREFIX", "jsoned_");

  // charset of the DB
  define("DB_CHARSET", "utf8");

  // main e-mail of the admin of the site
  define("EMAIL_CONFIG", "khnu2019@gmail.com");

  // is SMTP server used
  define("SMTP_SERVER", false);
?>