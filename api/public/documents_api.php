<?php
  require_once "../api.php";
  require_once "../../general_functions.php";
  require_once "../../classes/documents.php";

  final class DocumentsApi extends Api {
    protected $apiName = "posts";
    private $user      = false;
    private $dbh       = false;
    
    public function __construct($db_connect, $user_login, $user_token) {
      parent::__construct();

      $user = Users\getUser($db_connect, $user_login);
      if(!$user) {
        throw new Exception("Incorrect user login", 403);
      }
        
      if(!token_verify($user, $user_token, "docs")) {
        throw new Exception("Incorrect user token", 403);
      }
        
      $this->user = $user;
      $this->dbh  = $db_connect;
    }

    /**
     * Method: GET
     * Action: Get list of all documents
     * URL: http://localhost/jsoned/api/posts/
     * @return string
     */
    public function indexAction() {
      $documents = Docs\getAllDocumentsByAuthor($this->dbh, $this->user["login"]);
      
      if(empty($documents)) {
        return $this->response("Data not found", 404);
      } else {
        return $this->response($documents, 200);
      }
    }
    
    /**
     * Method: GET
     * Action: Get document by ID
     * URL: http://localhost/jsoned/api/posts/$id
     * @return string
     */
    public function viewAction() {
      if(!isset($this->requestUri[2])) {
        return $this->response("Invalid link", 422);
      }
      
      $id = $this->requestUri[2];
      $document = Docs\getDocumentByAuthor($this->dbh, $id, $this->user["login"]);

      if(empty($document)) {
        return $this->response("Data not found or have not enough permissions", 404);
      } else {
        return $this->response($document, 200);
      }
    }

    /**
     * Method: POST
     * Action: Create document
     * URL: http://localhost/jsoned/api/posts/
     * @return string
     */
    public function createAction() {
      $data = $this->getDocument();
      if(empty($data)) {
        return $this->response("The form data is empty", 422);
      }
      
      if(Docs\createDocument($this->dbh, $data)) {
        return $this->response("Document successfully created", 201);
      } else {
        return $this->response("Invalid data format", 422);
      }
    }

    /**
     * Method: PATCH
     * Action: Update document by ID
     * URL: http://localhost/jsoned/api/posts/$id
     * @return string
     */
    public function updateAction() {
      if(!isset($this->requestUri[2])) {
        return $this->response("Invalid link", 422);
      }

      $id = $this->requestUri[2];

      if(!empty($doc = Docs\getDocument($this->dbh, $id))) {
        if($doc["author"] !== $this->user["login"]) {
          return $this->response("You have not permission to update this document", 403);
        }
      } else {
        return $this->response("Document with such id does not exist", 404);
      }
        
      $data = $this->getDocument();
      if(empty($data)) {
        return $this->response("The raw data is empty", 422);
      }

      if(Docs\updateDocument($this->dbh, $id, $data)) {
        return $this->response("Document successfully updated", 200);
      } else {
        return $this->response("Invalid data or link", 422);
      }
    }

    /**
     * Method: DELETE
     * Action: Delete document by ID
     * http://localhost/jsoned/api/posts/$id
     * @return string
     */
    public function deleteAction() {
      if(!isset($this->requestUri[2])) {
        return $this->response("Invalid link", 422);
      }
      
      $id = $this->requestUri[2];

      if(!empty($doc = Docs\getDocument($this->dbh, $id))) {
        if($doc["author"] !== $this->user["login"]) {
          return $this->response("You have not permission to delete this document", 403);
        }
      } else {
        return $this->response("Document with such id does not exist", 404);
      }

      if(Docs\deleteDocument($this->dbh, $id)) {
        return $this->response("Document successfully deleted", 200);
      }
    }
  }
?>