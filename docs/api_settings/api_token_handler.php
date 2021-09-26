<?php
  session_start();
  require("../../general_functions.php");
  require("../../api/connect.php");

  foreach($_POST as $key => $value){
    if(preg_match("/(.+)_btn-(.)/", $key, $name)){
      break;
    }
  }
  
  if(!empty($name)) {
    $action = $name[1];
    $prefix = $name[2];
  } else {
    Redirect("api_settings");
  }
  
  switch($action){
    case "generate":
      $apiToken = generateApiToken($_SESSION["login"]);
      loadApiToken($dbh, $_SESSION["login"], $apiToken, $prefix);
      break;
    case "delete":
      deleteApiToken($dbh, $_SESSION["login"], $prefix);
      break;
  }

  Redirect("api_settings");
?>