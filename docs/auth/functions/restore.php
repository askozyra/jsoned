<?php
  session_start();

  require("../../../general_functions.php");
  require("../../../api/connect.php");
  require("../../../classes/users.php");

  $login = $_POST["login"] ?? null;
  $token = $_POST["csrf_token"] ?? null;
  
  if(token_isValid($token)){
    $user = Users\getUser($dbh, $login);
    if($user){
      if(isEmailExists($user)){
        $email = $user["email"];
        $new_password = formPassword($login);
        $salted_password = saltPassword($new_password, $user["salt"]);

        if(updatePassword($dbh, $login, $salted_password)){
          if(sendMail($email, $new_password)){
            setStatus("The message sent successfully! Please, check your email. :)", "success");
            Redirect("login");
          } else {
            setStatus("Email does not exist!", "error");
            Redirect("password_restoring");
          }
        }
      } else {
        setStatus("Email is not attached!", "error");
        Redirect("password_restoring");
      }
    } else {
      setStatus("Login is not exist!", "error");
      Redirect("password_restoring");
    }
  }
?>