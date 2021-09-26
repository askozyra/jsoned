<?php
  abstract class Api{
    protected $apiName = "";
    protected $requestUri = [];
    protected $requestParams = [];

    protected $method = '';     // Method: GET | POST | PATCH | DELETE
    protected $action = '';     // Name of the method

    public function __construct() {
      $this->requestUri = getRequestUri();
      $this->requestParams = $_REQUEST;

      // Definition of the method of the query
      switch($_SERVER['REQUEST_METHOD']) {
        case "GET":
        case "POST":
        case "PATCH":
        case "DELETE":
          $this->method = $_SERVER["REQUEST_METHOD"];
        break;
        
        default:
          throw new Exception("Unexpected Method");
        break;
      }
    }

    public function run() {
      // First 2 elements of the URI must be "api" and the name of the table
      try {
        $name_of_the_table = $this->requestUri[1] === "functions" ? $this->requestUri[2] : $this->requestUri[1];
  
        if($this->requestUri[0] !== "api" || $name_of_the_table !== $this->apiName) {
            throw new RuntimeException('API Not Found', 404);
        }
  
        $this->action = $this->getAction();
  
        if (method_exists($this, $this->action)) {
            return $this->{ $this->action }();
        } else {
            throw new RuntimeException('Invalid Method', 405);
        }
      } catch (Exception $e) {
        return false;
      }
    }

    protected function response($data, $status = 500) {
      header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
      return json_encode($data);
    }

    private function requestStatus($code) {
      $status = array(
        200 => 'OK',
        201 => 'Created',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unproccessable Entity',
        500 => 'Internal Server Error',
      );
      return ($status[$code]) ? $status[$code] : $status[500];
    }

    protected function getAction() {
      $method = $this->method;
      if($this->requestUri[1] === "functions") {
        if(isset($this->requestUri[3]))
          $id = $this->requestUri[3];
      } else {
        if(isset($this->requestUri[2]))
          $id = $this->requestUri[2];
      }

      switch ($method) {
        case 'GET':
          if(isset($id)) {
            return 'viewAction';
          } else {
            return 'indexAction';
          }
          break;
        case 'POST':
          return 'createAction';
          break;
        case 'PATCH':
          return 'updateAction';
          break;
        case 'DELETE':
          return 'deleteAction';
          break;
        default:
          return null;
          break;
      }
    }

    protected function getDocument() {
      switch($this->method) {
        case "POST":
          $data = json_decode(json_encode($_POST));
          if(!empty($data)) {
            $data->author = $_SERVER["HTTP_X_USER_LOGIN"];
          }
          if(isset($data->payload)) {
            $data->payload = json_decode($data->payload);
          }
          break;
        case "PATCH":
          $data = json_decode(file_get_contents("php://input"));
          break;
      }
      return $data;
    }

    abstract protected function indexAction();
    abstract protected function viewAction();
    abstract protected function createAction();
    abstract protected function updateAction();
    abstract protected function deleteAction();
  }
?>