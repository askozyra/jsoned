<?php
  session_start();

  require("api/connect.php");
  require("general_functions.php");

  if(isLogined($dbh)){
    Redirect("me");
  } else {
    Redirect("login");
  }
?>
