<?php
  session_start();

  require_once "../../config.php";
  require_once "../../general_functions.php";
  require_once "../../api/connect.php";
  
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
    <form class="auth" method="POST" action="functions/login">
      <input name="csrf_token" value="<?php echo create_csrf_token(); ?>" type="hidden" required>
      <input class="flat_input" name="login" placeholder="login" type="text" required>
      <input class="flat_input" name="password" placeholder="password" type="password" autocomplete="on" required>
      <input class="flat_input" value="Login" type="submit">
    </form>
    <span class="auth_offer">Forgot your password? <a href="password_restoring">Restore!</a></span>
    <span class="auth_offer">Still do not have account? <a href="registration">Register!</a></span>
  </main>
  
  <footer></footer>
  <script src="js/elems/messages.js" type="module"></script>
</body>
</html>