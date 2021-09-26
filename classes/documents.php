<?php
  namespace Docs;

  use Exception;

  require "documentValidator.php";
  
  function getAllDocuments($dbh) {
    $query = "SELECT * FROM " . DB_PREFIX . "documents";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    $res = $stmt->rowCount() !== 0 ? $stmt->fetchAll() : false;
    return $res;
  }

  function getAllDocumentsByAuthor($dbh, $author) {
    $query = "SELECT * FROM " . DB_PREFIX . "documents WHERE author='$author'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    $res = $stmt->rowCount() !== 0 ? $stmt->fetchAll() : false;
    return $res;
  }
  
  function getDocument($dbh, $id) {
    $query = "SELECT * FROM " . DB_PREFIX . "documents WHERE id=$id";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    $res = $stmt->rowCount() === 1 ? $stmt->fetch() : false;
    return $res;
  }
  
  function getDocumentByAuthor($dbh, $id, $login) {
    $query = "SELECT * FROM " . DB_PREFIX . "documents WHERE id=$id AND author='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    
    $res = $stmt->rowCount() === 1 ? $stmt->fetch() : false;
    return $res;
  }

  function createDocument($dbh, $data) {
    if(!isDocumentValid($data))
      return false;
  
    $title = $data->title ?? "title";
    $author = $data->author;
    $visibility = $data->visibility ?? "private";
    $payload = json_encode($data->payload) ?? "{}";
      
    $query = "INSERT INTO " . DB_PREFIX . "documents (title, author, visibility, payload)
    VALUES ('$title', '$author', '$visibility', '$payload')";

    $stmt = $dbh->prepare($query);
 
    return $stmt->execute();
  }
  
  function updateDocument($dbh, $id, $data) {
    try {
      $listOfKeys = [
        "title",
        "visibility",
        "payload"
      ];
    
      $comma = " ";
      $query = "UPDATE " . DB_PREFIX . "documents SET";
      foreach($data as $key => $value){
        if(empty($value) || !in_array($key, $listOfKeys))
        return false;
        
        $query .= $comma . "$key='$value'";
        $comma = ", ";
      }
      $query .= " WHERE id='$id'";
      
      $stmt = $dbh->prepare($query);
      
      $stmt->execute();
      
      return $stmt->rowCount() !== 0;
    } catch (Exception $e) {
      return false;
    }
  }
  
  function deleteDocument($dbh, $id) {
    $query = "DELETE FROM " . DB_PREFIX . "documents WHERE id=$id";
    $stmt = $dbh->prepare($query);
    
    $stmt->execute();
    
    return $stmt->rowCount() !== 0;
  }
?>