<?php
  session_start();
  
  require("../../general_functions.php");
  require("../../api/connect.php");
  require("../post_functions.php");  
  require("../../classes/users.php");
  require("../../classes/user_settings.php");

  $COOKIE = json_decode(json_encode($_COOKIE));
  
  $data = authentificationProcess($dbh);
  $user = $data["user"];
  $settings = $data["settings"];

  $USER_LOGIN = $user["login"];

  $API_TOKEN_DOCS = getApiToken($dbh, $USER_LOGIN, "d");
  $API_TOKEN_USERS = getApiToken($dbh, $USER_LOGIN, "u");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require "../../elems/head.php"; ?>
  <title>jsoned API - <?php echo $USER_LOGIN; ?></title>
</head>
<body>
  <header id="header_posts">
    <?php require("../../elems/menu.php"); ?>
    <?php require("../../elems/posts/header_themes.php"); ?>
  </header>

  <main id="api_settings">
    <div id="personal_area__nickname" class="api_settings__setting">
      <span>Welcome to API settings, <?php echo $USER_LOGIN; ?>! :)</span>
    </div>
    <form id="personal_area__description_field" class="api_settings__setting" method="POST" action="functions/api_token_handler">
      <span>Docs API token</span>
      <?php
        $api_token_input = "<input name=\"api_token\" type=\"text\" class=\"flat_input\" placeholder=\"here will be the API token\" maxlength=\"255\" readonly";
        if(!empty($API_TOKEN_DOCS)) {
          $api_token_input .= " value=\"$API_TOKEN_DOCS\">";
          $copy_btn = "<input name=\"copy_btn\" class=\"flat_input\" value=\"C O P Y\" type=\"button\">";
          $delete_btn = "<input name=\"delete_btn-d\" class=\"relief-btn relief-btn-default\" value=\"D E L E T E\" type=\"submit\">";
          
          echo $api_token_input;
          echo $copy_btn;
          echo $delete_btn;
        } else {
          $api_token_input .= ">";
          $generate_btn = "<input name=\"generate_btn-d\" class=\"relief-btn relief-btn-default\" value=\"G E N E R A T E\" type=\"submit\">";

          echo $api_token_input;
          echo $generate_btn;
        }
      ?>
    </form>
    <span></span>
    <form id="personal_area__description_field" class="api_settings__setting" method="POST" action="functions/api_token_handler">
      <span>Users API token</span>
      <?php
        $api_token_input = "<input name=\"api_token\" type=\"text\" class=\"flat_input\" placeholder=\"here will be the API token\" maxlength=\"255\" readonly";
        if(!empty($API_TOKEN_USERS)) {
          $api_token_input .= " value=\"$API_TOKEN_USERS\">";
          $copy_btn = "<input name=\"copy_btn\" class=\"flat_input\" value=\"C O P Y\" type=\"button\">";
          $delete_btn = "<input name=\"delete_btn-u\" class=\"relief-btn relief-btn-default\" value=\"D E L E T E\" type=\"submit\">";
          
          echo $api_token_input;
          echo $copy_btn;
          echo $delete_btn;
        } else {
          $api_token_input .= ">";
          $generate_btn = "<input name=\"generate_btn-u\" class=\"relief-btn relief-btn-default\" value=\"G E N E R A T E\" type=\"submit\">";

          echo $api_token_input;
          echo $generate_btn;
        }
      ?>
    </form>
  </main>

  <?php require "../../elems/scripts_src.php"; ?>
  <script src="js/docs/api_settings.js" type="module"></script>
</body>
</html>