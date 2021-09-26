<?php
  namespace Users;

  use Exception;

  function getAllUsers($dbh) {
    $query = "SELECT * FROM " . DB_PREFIX . "users";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    $res = $stmt->rowCount() !== 0 ? $stmt->fetchAll() : false;
    return $res;
  }
  
  function getUser($dbh, $login) {
    $query = "SELECT * FROM " . DB_PREFIX . "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    return $stmt->rowCount() === 1 ? $stmt->fetch() : false;
  }
  
  function createUser($dbh, $data) {
    $login = $data->login;
    $password = $data->password;

    $salt = generateSalt();
    $salted_password = saltPassword($password, $salt);

    $query = "INSERT INTO " . DB_PREFIX . "users (login, password, salt) VALUES ('$login', '$salted_password', '$salt')";
    $stmt = $dbh->prepare($query);
    
    return $stmt->execute();
  }
  
  function updateUser($dbh, $login, $data) {
    try {
      $listOfKeys = [
        "description",
        "email",
        "password"
      ];

      $comma = " ";
      $query = "UPDATE " . DB_PREFIX . "users SET";
      foreach($data as $key => $value){
        if(is_null($value) || !in_array($key, $listOfKeys))
          return false;
        
        if($key === "password") {
          $old = $value->old;
          $new = $value->new;

          $user = getUser($dbh, $login);
          if(password_isValid($old, $user["password"], $user["salt"])){
            $new_salted = saltPassword($new, $user["salt"]);
            $query .= $comma . "password='$new_salted'";
            $comma = ", ";
            continue;
          } else {
            return false;
            continue;
          }
        }

        $query .= $comma . "$key='$value'";
        $comma = ", ";
      }
      $query .= " WHERE login='$login'";
      
      $stmt = $dbh->prepare($query);
      
      $stmt->execute();
      
      return $stmt->rowCount() !== 0;
    } catch (Exception $e) {
      return false;
    }
  }
  
  function changePassword($dbh, $login, $old, $new) {
    $user = getUser($dbh, $login);
    
    if(password_isValid($old, $user["password"], $user["salt"])){
      $new_salted = saltPassword($new, $user["salt"]);
      $stmt = $dbh->prepare("UPDATE " . DB_PREFIX . "users SET password='$new_salted' WHERE login='$login'");
      return $stmt->execute();
    }
    
    return false;
  };

  function deleteUser($dbh, $login) {
    $query = "DELETE FROM " . DB_PREFIX . "users WHERE login='$login'";
    $stmt = $dbh->prepare($query);
    
    $stmt->execute();
    
    return $stmt->rowCount() !== 0;
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
?>