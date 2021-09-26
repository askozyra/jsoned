<?php
  session_start();

  require("api/connect.php");
  require("docs/functions.php");

  if(isLogined($dbh)){
    Redirect("me");
  } else {
    Redirect("login");
  }
?>