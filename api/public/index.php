<?php
  header("Content-type: json/application");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: *");
  header("Access-Control-Allow-Credentials: true");

  require("../connect.php");
  require("../../general_functions.php");
  require("documents_api.php");
  require("users_api.php");
  require("user_settings_api.php");

  if(!isset($_SERVER["HTTP_X_USER_LOGIN"]) || !isset($_SERVER["HTTP_X_USER_TOKEN"])) {
    http_response_code(403);
    return;
  }

  $login = $_SERVER["HTTP_X_USER_LOGIN"];
  $token = $_SERVER["HTTP_X_USER_TOKEN"];

  $type = getRequestUri()[1];

  try {
    switch($type) {
      case "posts":
          $docsApi = new DocumentsApi($dbh, $login, $token);
          echo $docsApi->run();
        break;
        
      case "users":
        $usersApi = new UsersApi($dbh, $login, $token);
        echo $usersApi->run();
        break;
        
      case "user_settings":
        $userSettingsApi = new UserSettingsApi($dbh, $login, $token);
        echo $userSettingsApi->run();
        break;
    }
  } catch(Exception $e) {
    echo response($e->getMessage(), $e->getCode());
  }
?>