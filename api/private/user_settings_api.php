<?php
  require_once "../../../config.php";
  
  require_once ABSOLUTE_PATH . "/general_functions.php";
  require_once ABSOLUTE_PATH . "/api/api.php";
  require_once ABSOLUTE_PATH . "/classes/user_settings.php";
  require_once ABSOLUTE_PATH . "/classes/users.php";

  final class UserSettingsApi extends Api {
    protected $apiName = "user_settings";
    private $user = false;
    private $dbh = false;
    
    public function __construct($db_connect, $user_login, $user_token) {
      try {
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
      } catch (Exception $e) {
        $this->error = true;
      }
    }

    /**
     * Method: GET
     * Action: Get list of all settings
     * URL: http://localhost/jsoned/api/functions/user_settings/
     * @return string
     */
    public function indexAction() {
      $settings = UserSettings\getAllSettings($this->dbh, $this->user["login"]);
      
      if(empty($settings)) {
        return $this->response("Data not found", 404);
      } else {
        return $this->response($settings, 200);
      }
    }
      
    /**
     * Method: GET
     * Action: Get setting by name
     * URL: http://localhost/jsoned/api/functions/user_settings/$setting
     * @return string
     */
    public function viewAction() {
      try {
        if(!isset($this->requestUri[3])) {
          return $this->response("Invalid link", 422);
        }
        
        $query = $this->requestUri[3];
        $setting = UserSettings\getSetting($this->dbh, $this->user["login"], $query);
        
        if(empty($setting)) {
          return $this->response("Data not found", 404);
        } else {
          return $this->response($setting, 200);
        }
      } catch(Exception) {
        return $this->response("Incorrect field name", 404);
      }
    }


    /**
     * Method: POST
     * URL: http://localhost/jsoned/api/functions/user_settings/
     * Unsupported
     * @return string
     */
    public function createAction() {
      return $this->response("You can only set new values to the existing settings", 403);
    }

    /**
     * Method: PATCH
     * Action: Update setting by name
     * URL: http://localhost/jsoned/api/functions/user_settings/$setting
     * @return string
     */
    public function updateAction() {
      $data = $this->getDocument();
 
      if(UserSettings\setSettings($this->dbh, $this->user["login"], $data)) {
        return $this->response("Settings successfully updated", 200);
      } else {
        return $this->response("Invalid data or link", 422);
      }
    }


    /**
     * Method: DELETE
     * Action: Reset all settings
     * URL: http://localhost/jsoned/api/functions/user_settings/
     * @return string
     */
    public function deleteAction() {
      if(UserSettings\resetSettings($this->dbh, $this->user["login"])) {
        return $this->response("Settings successfully reseted", 200);
      } else {
        return $this->response("Critical error: settings does not exist", 404);
      }
    }
  }
?>