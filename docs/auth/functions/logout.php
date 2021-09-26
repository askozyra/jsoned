<?php
  session_start();

  require_once "../../../config.php";

  require_once ABSOLUTE_PATH . "/api/connect.php";
  require_once ABSOLUTE_PATH . "/general_functions.php";
  require_once ABSOLUTE_PATH . "/classes/users.php";

  $login = $_REQUEST["login"] ?? null;
  $token = $_REQUEST["csrf_token"] ?? null;
  
  if(!token_isValid($token)){
    header("Location: " . $_SERVER["HTTP_REFERER"]);
  }

  unauthorizeUser($dbh, $login);
  if(isset($_POST["delete_account_btn"])) {
    Users\deleteUser($dbh, $login);
  }
  Redirect("login");
?>