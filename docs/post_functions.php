<?php
  function getCountOfPublicPosts($dbh){
    $query = "SELECT COUNT(*) as count FROM " . DB_PREFIX .  "documents WHERE visibility='public'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch()["count"];
    
    return $count;
  }

  function getCountOfUserPosts($dbh, $login){
    $query = "SELECT COUNT(*) as count FROM " . DB_PREFIX .  "documents WHERE author='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch()["count"];
    
    return $count;
  }

  function getCountOfPages($count_of_data, $data_per_page){
    $countOfPages = floor($count_of_data / $data_per_page);
    $count_of_data % $data_per_page > 0 ? $countOfPages++ : $countOfPages;

    return intval($countOfPages);
  }

  function getPublicPosts($dbh){
    $query = "SELECT * FROM " . DB_PREFIX .  "documents WHERE visibility='public'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $postsList = $stmt->fetchAll();

    return $postsList;
  }

  function getUserPosts($dbh, $login){
    $query = "SELECT * FROM " . DB_PREFIX .  "documents WHERE author='$login'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $postsList = $stmt->fetchAll();
    
    return $postsList;
  }


  function getPostsByPage($dbh, $page, $posts_per_page){
    $query = "SELECT * FROM " . DB_PREFIX .  "documents WHERE visibility='public' LIMIT " . ($page - 1) * $posts_per_page . ", $posts_per_page";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $postsList = $stmt->fetchAll();
    
    return $postsList;
  }
  
  function getUserPostsByPage($dbh, $login, $page, $posts_per_page){
    $query = "SELECT * FROM " . DB_PREFIX .  "documents WHERE author='$login' LIMIT " . ($page - 1) * $posts_per_page . ", " . $posts_per_page;
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $postsList = $stmt->fetchAll();
    
    return $postsList;
  }
?>