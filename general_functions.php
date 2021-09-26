<?php
  $root = $_SERVER["DOCUMENT_ROOT"];
  require_once "$root/jsoned/config.php";

  function login_isValid($login){
    return isset($login) && $login === $_SESSION["login"];
  }

  function updateActivity($dbh, $login){
    $last_activity = date("Y-m-d H:i:s");
    $query = "UPDATE " . DB_PREFIX .  "users SET lastEntrance='$last_activity' WHERE login='$login'";
    $stmt = $dbh->prepare($query);

    return $stmt->execute();
  }
  
  // function getPost($dbh, $id, $login, $authId){
  //   $query = "SELECT * FROM " . DB_PREFIX .  "documents WHERE id='$id' AND author IN " .
  //   "(SELECT login FROM " . DB_PREFIX .  "users WHERE login='$login' AND (authId='$authId' OR authId IS NULL))";
  //   $stmt = $dbh->prepare($query);
  //   $stmt->execute();
  
  //   if($stmt->rowCount() === 1){
  //     $post = $stmt->fetch();
  //     return $post;
  //   } else {
  //     return false;
  //   }
  // }

/**
  * @param usage
  * @param string $message actually it is the message
  * @param string $type of the message [error | success]
  * @return html
  */
  function setStatus($message, $type){
    $_SESSION["status"] = [ "message" => $message, "type" => $type ];
  }

  function formPassword($login){
    return substr(md5($login . time()), 0, 24);
  }

  function sendMail($email, $message){
    $headers  = "Content-type: text/html; charset=windows-1251 \r\n"; 
    $headers .= "From: khnu2019@gmail.com\r\n"; 
    $headers .= "Reply-To: $email\r\n"; 

    return mail($email, "Password restoring", $message, $headers);
  }
  
  function isEmailExists($user){
    return isset($user["email"]);
  }

  function createAuthId($login){
    return md5($login . $_COOKIE["PHPSESSID"] . $_SERVER["REMOTE_ADDR"]);
  }

  function generate_csrf_token(){
    return substr(str_shuffle("qwertyuiopQWERTYUIOP[]1234567890!()@#%^&*asdfghjkl'ZXCVBNM,.") , 0, 12);
  }

  function token_verify($user, $token, $prefix) {
    return !empty($user["api_token_$prefix"]) && $user["api_token_$prefix"] === $token;
  }

  function getApiToken($dbh, $login, $prefix) {
    $p = getPrefixDecryption($prefix);
    $query = "SELECT * FROM " . DB_PREFIX .  "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    if(!$stmt->execute())
      return false;

    $user = $stmt->fetch();
      
    if(empty($user) || empty($user["api_token_$p"]))
      return false;
    
    return $user["api_token_$p"];
  }

  function loadApiToken($dbh, $login, $apiToken, $prefix) {
    $p = getPrefixDecryption($prefix);
    $query = "UPDATE " . DB_PREFIX .  "users SET api_token_$p='$apiToken' WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    if(!$stmt->execute())
      return false;
  
    return $stmt->fetch() !== 0;
  }

  function generateApiToken($login) {
    return md5($login . md5( (new DateTime())->getTimestamp() ) );
  }
  
  function getPrefixDecryption($prefix) {
    switch($prefix) {
      case "d":
        return "docs";
        break;

      case "u":
        return "users";
        break;
    }
  }

  /**
   * @param string $login login of the user
   * @param string $prefix [d]ocs | [u]sers
   * @return string
   */
  function deleteApiToken($dbh, $login, $prefix) {
    $p = getPrefixDecryption($prefix);
    $query = "UPDATE " . DB_PREFIX .  "users SET api_token_$p=null WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    if(!$stmt->execute())
      return false;
  
    return $stmt->fetch() !== 0;
  }

  function create_csrf_token(){
    return $_SESSION["csrf_token"] = generate_csrf_token();
  }

  function delete_csrf_token(){
    unset($_SESSION["csrf_token"]);
  }

  function setUserAuthId($dbh, $login, $authId){
    $query = "UPDATE " . DB_PREFIX .  "users SET authId=('$authId') WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    return $stmt->execute();
  }

  function authorizeUser($dbh, $login, $remember){
    $authId = createAuthId($login);
    
    $_SESSION["login"] = $login;
    $_SESSION["authId"] = $authId;
    $_SESSION["auth"] = true;

    if($remember){
      setUserAuthId($dbh, $login, $authId);

      setcookie("login", $login, time() + 60 * 60 * 24 * 30, "/curl2");
      setcookie("authId", $authId, time() + 60 * 60 * 24 * 30, "/curl2");
    }
  }

  function token_isValid($token){
    return isset($_SESSION["csrf_token"]) && $token === $_SESSION["csrf_token"];
  }

  function unauthorizeUser($dbh, $login){
    $query = "UPDATE " . DB_PREFIX .  "users SET authId=null WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    session_destroy();

    setcookie("login", "", time() - 3600, "/curl2");
    setcookie("authId", "", time() - 3600, "/curl2");
  }

  function Redirect($url){
    header("Location: " . TRANSFER_PROTOCOL . "://" . DOMAIN_NAME . "/" . ROOT_NAME . "/" . $url);
  }
  
  function authentificationProcess($dbh) {
    if(isLogined($dbh)) {
      $user = Users\getUser($dbh, $_SESSION["login"]);
      $settings = UserSettings\getAllSettings($dbh, $_SESSION["login"]);
      updateActivity($dbh, $_SESSION["login"]);
    } else {
      Redirect("login");
    }
    
    return ["user" => $user, "settings" => $settings];
  }

  function isLogined($dbh){
    if(empty($_SESSION["login"]) || (empty($_SESSION["auth"]))){
      if(!empty($_COOKIE["login"]) && !empty($_COOKIE["authId"])){
        $login = $_COOKIE["login"];

        $query = "SELECT * FROM " . DB_PREFIX .  "users WHERE login='$login'";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $user = $stmt->fetch();
    
        if(($user["authId"] === $_COOKIE["authId"]) && ($login === $_COOKIE["login"])){
          $_SESSION["login"] = $user["login"];
          $_SESSION["auth"] = true;

          return true;
        } else {
          return false;
        }
      }
    } else return true;

    return false;
  }

  function generateSalt(){
    $salt = '';

    for($i = 0; $i < 8; $i++){
      $salt .= chr(mt_rand(33, 126));
    }
    
    return $salt;
  }

  function saltPassword($password, $salt){
    return md5( md5($password) . $salt );
  }

  function password_isValid($password, $userPassword, $userSalt){
    return saltPassword($password, $userSalt) === $userPassword;
  }

  function changePassword($dbh, $login, $old, $new){
    $user = Users\getUser($dbh, $login);
    
    if(password_isValid($old, $user["password"], $user["salt"])){
      $new_salted = saltPassword($new, $user["salt"]);
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX .  "users SET password='$new_salted' WHERE login='$login'");
      return $stmt->execute();
    }
    
    return false;
  }

  function createUser($dbh, $login, $password){
    $salt = generateSalt();
    $salted_password = saltPassword($password, $salt);

    $query = "INSERT INTO " . DB_PREFIX .  "users (login, password, salt) VALUES ('$login', '$salted_password', '$salt')";
    $stmt = $dbh->prepare($query);
    
    return $stmt->execute();
  }
  
  function isUserExists($dbh, $login){
    $query = "SELECT * FROM " . DB_PREFIX .  "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    $stmt->execute();
    
    $user = $stmt->fetch();

    return !empty($user);
  }

  function checkUser($dbh, $login, $password){
    if(empty($login) && empty($password)){
      setStatus("Login and password are empty!", "error");
      return false;
    }

    $user = Users\getUser($dbh, $login);
    
    if(empty($user)){
      setStatus("Login is not exist!", "error");
      return false;
    }

    if(!password_isValid($password, $user["password"], $user["salt"])){
      setStatus("Password is incorrect!", "error");
      return false;
    }
    
    setStatus("You are successfully logined! :)", "success");
    return true;
  }
  
  function setDescription($dbh, $login, $description){
    if(empty($description))
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX .  "users SET description=null WHERE login='$login'");
    else
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX .  "users SET description='$description' WHERE login='$login'");
  
    return $stmt->execute();
  }
  
  function getDescription($dbh, $login){
    $query = "SELECT * FROM " . DB_PREFIX .  "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    return $stmt->fetch()["description"];
  }
  
  function updatePassword($dbh, $login, $password){
    $query = "UPDATE " . DB_PREFIX .  "users SET password='$password' WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    return $stmt->execute();
  }
  
  function setEmail($dbh, $login, $email){
    if(empty($email))
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX .  "users SET email=null WHERE login='$login'");
    else
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX .  "users SET email='$email' WHERE login='$login'");
    
    return $stmt->execute();
  }

  function getEmail($dbh, $login){
    $query = "SELECT * FROM " . DB_PREFIX .  "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    return $stmt->fetch()["email"];
  }

  function getRequestUri() {
    $requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    while($requestUri[0] !== "api")
      array_shift($requestUri);
      
    return $requestUri;
  }
  
  function createPost($dbh, $data){
    if(isDocumentValid($data)){
      $author = $data->author;
      $visibility = $data->visibility;
      $payload = json_encode($data->payload);

      $query = "INSERT INTO documents (title, author, visibility, payload)
                VALUES (:title, '$author', '$visibility', '$payload')";
  
      $stmt = $dbh->prepare($query);
      $stmt->bindParam(':title', $data->title);

      return $stmt->execute();
    }

    return false;
  }

  function getAuthId($login){
    return md5($login . $_COOKIE["PHPSESSID"] . $_SERVER["REMOTE_ADDR"]);
  }

  function response($data, $status = 500) {
    header("HTTP/1.1 " . $status . " " . requestStatus($status));
    return json_encode($data);
  }

  function requestStatus($code) {
    $status = array(
      200 => 'OK',
      201 => 'Created',
      400 => 'Bad Request',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      422 => 'Unproccessable Entity',
      500 => 'Internal Server Error',
    );
    return ($status[$code]) ? $status[$code] : $status[500];
  }
?>