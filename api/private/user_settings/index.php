<?php
  session_start();

  require_once "../../../config.php";

  require_once(ABSOLUTE_PATH . "/api/private/user_settings_api.php");
  require_once(ABSOLUTE_PATH . "/api/connect.php");
  
  header("Content-type: json/application");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: *");
  header("Access-Control-Allow-Credentials: true");

  $login = $_SERVER["HTTP_X_USER_LOGIN"] ?? null;
  $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? null;
  $method = $_SERVER["REQUEST_METHOD"] ?? null;
  $query = $_GET["query"] ?? null;
  
  $params = explode("/", $query);
  $type = $params[0] ?? null;
  $id = $params[1] ?? null;

  if(!token_isValid($token) || !login_isValid($login)){
    echo response("Access denied", 403);
    return;
  }

  try {
    $usersSettingsApi = new UserSettingsApi($dbh, $login, $token);
    echo $usersSettingsApi->run();
  } catch (Exception $e) {
    echo response($e->getMessage(), $e->getCode());
  }
?>