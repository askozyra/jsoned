<?php
  require_once "../../../config.php";

  require_once ABSOLUTE_PATH . "/general_functions.php";
  require_once ABSOLUTE_PATH . "/api/api.php";
  require_once ABSOLUTE_PATH . "/classes/users.php";

  final class UsersApi extends Api {
    protected $apiName = "users";
    private $user = false;
    private $dbh = false;
    
    public function __construct($db_connect, $user_login, $user_token) {
      parent::__construct();
      
      $user = Users\getUser($db_connect, $user_login);
      if(!$user) {
        throw new Exception("Incorrect user login", 403);
      }
      
      if(!token_isValid($user_token)) {
        throw new Exception("Access denied", 403);
      }
      
      $this->user = $user;
      $this->dbh = $db_connect;
    }

    /**
     * Method: GET
     * Action: Get list of all users
     * URL: http://localhost/jsoned/api/functions/users/
     * @return string
     */
    public function indexAction() {
      $users = Users\getAllUsers($this->dbh);
      
      if(empty($users)) {
        return $this->response("Data not found", 404);
      } else {
        foreach($users as $user) {
          $result[] = $this->formUser($user);
        }
        return $this->response($result, 200);
      }
    }
      
    /**
     * Method: GET
     * Action: Get user by login
     * URL: http://localhost/jsoned/api/functions/users/$login
     * @return string
     */
    public function viewAction() {
      if(!isset($this->requestUri[3])) {
        return $this->response("Invalid link", 422);
      }
      
      $user_login = $this->requestUri[3];
      $user = Users\getUser($this->dbh, $user_login);
      
      if(empty($user)) {
        return $this->response("Data not found", 404);
      } else {
        if($user["login"] === $this->user["login"]) {
          return $this->response($user, 200);
        } else {
          return $this->response($this->formUser($user), 200);
        }
      }
    }

    /**
     * Method: POST
     * URL: http://localhost/jsoned/api/functions/users/
     * Unsupported
     * @return string
     */
    public function createAction() {
      return $this->response("User creation is not support", 404);
    }

    /**
     * Method: PATCH
     * Action: Update user
     * URL: http://localhost/jsoned/api/functions/users/$login
     * @return string
     */
    public function updateAction() {
      if(!isset($this->requestUri[3])) {
        return $this->response("Invalid link", 422);
      }
      
      $login = $this->requestUri[3];
      if($this->user["login"] !== $login) {
        return $this->response("You can update only your account", 403);
      }

      $data = $this->getDocument();
        
      if(Users\updateUser($this->dbh, $login, $data)) {
        return $this->response("User successfully updated", 200);
      } else {
        return $this->response("Invalid data or link", 422);
      }
    }

    /**
     * Method: DELETE
     * Action: Delete user by login
     * URL: http://localhost/jsoned/api/functions/users/$login
     * @return string
     */
    public function deleteAction() {
      if(!isset($this->requestUri[3])) {
        return $this->response("Invalid link", 422);
      }
      
      $login = $this->requestUri[3];
      if($this->user["login"] !== $login) {
        return $this->response("You can delete only your account", 403);
      }

      if(Users\deleteUser($this->dbh, $login)) {
        unauthorizeUser($this->dbh, $login);
        return $this->response("User successfully deleted", 200);
      } else {
        return $this->response("Invalid data or link", 422);
      }
    }

    private function formUser($user) {
      return [
        "login" => $user["login"],
        "description" => $user["description"],
        "last_online" => $user["lastEntrance"]
      ];
    }
  }
?>