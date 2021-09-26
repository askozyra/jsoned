<?php
  session_start();
  require("../../general_functions.php");
  require_once("../../api/connect.php");

  if(isLogined($dbh))
    Redirect("me");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require("../../elems/head.php"); ?>  
  <title>Login</title>
</head>
<body>
  <header>
  </header>

  <main class="authentication">
    <span class="auth_offer">Enter your login in the field and wait for a message in the email with a new password</span>
    <form class="auth" method="POST" action="functions/restore">
      <input name="csrf_token" value="<?php echo create_csrf_token(); ?>" type="hidden" required>
      <input class="flat_input" name="login" placeholder="login" type="text" required>
      <input class="flat_input" value="Send" type="submit">
    </form>
    <span class="auth_offer"><a href="login">Return to login</a></span>
  </main>
  
  <footer></footer>
  <script src="js/elems/messages.js"></script>
</body>
</html>