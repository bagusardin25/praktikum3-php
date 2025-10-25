<?php
class Database
{
  private $host;
  private $user;
  private $pass;
  private $dbname;
  private $conn;

  public function __construct()
  {
    $this->host = DB_HOST;
    $this->user = DB_USER;
    $this->pass = DB_PASS;
    $this->dbname = DB_NAME;
    $this->connect();
  }
  private function connect()
  {
    $this->conn = new mysqli(
      $this->host,
      $this->user,
      $this->pass,
      $this->dbname
    );
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }
  public function getConnection()
  {
    return $this->conn;
  }
  public function __destruct()
  {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}
