<?php
  class db{
    private $dbHost ='79.148.236.236';
    private $dbUser = 'falor_fralg';
    private $dbPass = 'fralg100@gmail.com';
    private $dbName = 'falorente_reservas';
    //conección 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnexion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnexion;
    }
  }