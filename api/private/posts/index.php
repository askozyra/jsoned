<?php
  session_start();

  require_once "../../../config.php";

  require_once("../documents_api.php");
  require_once("../../../general_functions.php");
  require_once("../../connect.php");
  
  header("Content-type: json/application");
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: *");
  header("Access-Control-Allow-Credentials: true");

  $login = $_SERVER["HTTP_X_USER_LOGIN"] ?? null;
  $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? null;
  $method = $_SERVER["REQUEST_METHOD"] ?? null;
  $query = $_GET["query"] ?? null;
  
  if(!token_isValid($token) || !login_isValid($login)){
    echo response("Access denied", 403);
    return;
  }

  try {
    $docsApi = new DocumentsApi($dbh, $login, $token);
    echo $docsApi->run();
  } catch (Exception $e) {
    echo response($e->getMessage(), $e->getCode());
  }

?>