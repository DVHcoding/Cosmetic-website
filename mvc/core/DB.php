<?php

class DB
{
  private static $instance = null;

  public $con;
  protected $servername = "localhost";
  protected $username = "root";
  protected $password = "";
  protected $dbname = "datasale";


  private function __construct()
  {
    $this->con = mysqli_connect($this->servername, $this->username, $this->password);
    mysqli_select_db($this->con, $this->dbname);
    mysqli_query($this->con, "SET NAMES 'utf8'");
  }

  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new DB();
    }

    return self::$instance;
  }
}


?>