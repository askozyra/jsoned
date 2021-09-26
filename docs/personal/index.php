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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require("../../elems/head.php"); ?>
  <title>Main - <?php echo $_SESSION["login"]; ?></title>
</head>
<body>
  <header id="header_posts">
    <?php require("../../elems/menu.php"); ?>
    <?php require("../../elems/posts/header_themes.php"); ?>
  </header>

  <main id="personal_area">
    <div id="personal_area__nickname" class="personal_area__setting">
      <span>Hello, <?php echo $_SESSION["login"]; ?>! :)</span>
    </div>
    <form id="personal_area__email_field" class="personal_area__setting" method="POST">
      <span>email for restoring password</span>
      <input name="email" class="flat_input flat_input-bg-transparent" value="<?php echo getEmail($dbh, $_SESSION["login"]); ?>" placeholder="email" type="email">
      <input name="email_btn" class="relief-btn relief-btn-default" value="S A V E" type="submit">
    </form>
    <form id="personal_area__description_field" class="personal_area__setting" method="POST">
      <span>description</span>
      <textarea name="description" rows="5" cols="50" class="flat_input flat_input-bg-transparent flat_textarea" placeholder="tell something about yourself" maxlength="255"><?php echo getDescription($dbh, $_SESSION["login"]); ?></textarea>
      <input name="description_btn" class="relief-btn relief-btn-default" value="S A V E" type="submit">
    </form>
    <form id="personal_area__password_changing" class="personal_area__setting" method="PATCH">
      <span>change password</span>
      <input name="old_pass" autocomplete="on" class="flat_input flat_input-bg-transparent" placeholder="old password" type="password" required>
      <input name="new_pass" autocomplete="on" class="flat_input flat_input-bg-transparent" placeholder="new password" type="password" required>
      <input name="new_pass_repeat" autocomplete="on" class="flat_input flat_input-bg-transparent" placeholder="new password again" type="password" required>
      <input name="password_btn" class="relief-btn relief-btn-default" value="C O N F I R M" type="submit">
    </form>
    <form id="personal_area__logout" class="personal_area__setting" method="POST" action="docs/auth/functions/logout.php">
      <input name="csrf_token" value="<?php echo $CSRF_TOKEN; ?>" type="hidden" required>
      <input name="login" value="<?php echo $_SESSION["login"]; ?>" type="hidden" required>
      <input name="logout_btn" class="relief-btn relief-btn-default" value="L O G O U T" type="submit">
      <span>or</span>
      <input name="delete_account_btn" class="flat_input" value="DELETE ACCOUNT" type="submit">
    </form>
    <form id="personal_area__count_of_docs" class="personal_area__setting" method="POST">
      <span>count of docs on page</span>
      <input name="count_of_docs" class="flat_input flat_input-bg-transparent" value="<?php echo $settings["count_of_docs"]; ?>" placeholder="multiple of 4 is recommended" type="text">
      <input name="count_of_docs_btn" class="relief-btn relief-btn-default" value="S A V E" type="submit">
    </form>
    <form id="personal_area__smoothness_of_anims" class="personal_area__setting" method="POST">
      <label>
        < smoothness of anims
        <input name="smoothness_of_anims_chk" class="flat_input flat_input-bg-transparent" type="checkbox" <?php if($settings["smoothness_of_anims"]) echo "checked"; ?>>
        >
      </label>
    </form>
    <a id="personal_area__posts_link" class="personal_area__setting" href="posts">posts</a>
    <span></span>
    <a id="personal_area__drafts_link" class="personal_area__setting" href="drafts">drafts</a>
  </main>
  
  <script>
    const USER_LOGIN = "<?php echo $_SESSION["login"]; ?>";
  </script>
  <?php require "../../elems/scripts_src.php"; ?>
  <script src="js/docs/personal.js" type="module"></script>
</body>
</html>