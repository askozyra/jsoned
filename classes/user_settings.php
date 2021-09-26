<?php
  namespace UserSettings;

  use Exception;

  function getAllSettings($dbh, $user_login) {
    $query = "SELECT * FROM " . DB_PREFIX . "user_settings WHERE user_login='$user_login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    return $stmt->rowCount() === 1 ? $stmt->fetch() : false;
  }

  function getSetting($dbh, $user_login, $setting) {
    $query = "SELECT $setting FROM " . DB_PREFIX . "user_settings WHERE user_login='$user_login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    return $stmt->rowCount() === 1 ? $stmt->fetch() : false;
  }
  
  function setSettings($dbh, $user_login, $settings) {
    try {
      $listOfKeys = [
        "count_of_docs",
        "smoothness_of_anims"
      ];
    
      $comma = " ";
      $query = "UPDATE " . DB_PREFIX . "user_settings SET";
      foreach($settings as $key => $value){
        if(is_null($value) || !in_array($key, $listOfKeys))
          return false;
        
        $query .= $comma . "$key='$value'";
        $comma = ", ";
      }
      $query .= " WHERE user_login='$user_login'";
      
      $stmt = $dbh->prepare($query);
      
      $stmt->execute();
      
      return $stmt->rowCount() !== 0;
    } catch (Exception $e) {
      return false;
    }
  }
  
  function resetSettings($dbh, $user_login) {
    try {
      $query = "UPDATE " . DB_PREFIX . "user_settings SET count_of_docs='8', smoothness_of_anims=0";
      $query .= " WHERE user_login='$user_login'";
      
      $stmt = $dbh->prepare($query);
      
      $stmt->execute();
      
      return $stmt->rowCount() !== 0;
    } catch (Exception $e) {
      return false;
    }
  }
?>