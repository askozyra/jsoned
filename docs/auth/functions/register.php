<?php
  session_start();

  require("../../../general_functions.php");
  require("../../../api/connect.php");
  require("../../../classes/users.php");

  $login = $_POST["login"] ?? null;
  $password = $_POST["password"] ?? null;
  $token = $_POST["csrf_token"] ?? null;

  $data = json_decode(json_encode(["login" => $login, "password" => $password]));

  if(token_isValid($token) && !empty($login) && !empty($password)) {
    if(!isUserExists($dbh, $login)) {
      if(Users\createUser($dbh, $data))
        authorizeUser($dbh, $login, false);
    } else {
      setStatus("User already exists, try another login!", "error");
    }
  }

  Redirect("registration");
?>