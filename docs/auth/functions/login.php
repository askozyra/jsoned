<?php
  session_start();

  require("../../../config.php");
  require("../../../general_functions.php");
  require("../../../api/connect.php");
  require("../../../classes/users.php");

  $login = $_POST["login"] ?? null;
  $password = $_POST["password"] ?? null;
  $token = $_POST["csrf_token"] ?? null;

  if(token_isValid($token) && !empty($login) && !empty($password)){
    if(!token_isValid($token))
      Redirect("login");

    $user = Users\getUser($dbh, $login);

    if($user){
      if(password_isValid($password, $user["password"], $user["salt"])){
        authorizeUser($dbh, $login, false);
        setStatus("You are successfully logined! :)", "success");
        delete_csrf_token();

        Redirect("me");
      }
    }
  }
  
  Redirect("login");
?>