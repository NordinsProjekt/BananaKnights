<?php

abstract class PDOHandler
{
    private $database = "bananaknights";
    private $host =  "localhost";
    private $userName = "root";
    private $password = "";
    private $charset = "utf8mb4";

    abstract protected function GetAll();

    function __destruct()
    {
        
    }
    
    protected function Connect()
    {
        try
        {
            $dsn = "mysql:host=" .$this->host . ";dbname=" .$this->database. ";charset=" .$this->charset;
            $pdo = new PDO($dsn,$this->userName, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } 
        catch (\Throwable $e) 
        {
            $_SESSION['Message'] =  "Connection failed: " .$e->getMessage();
            die();
        }
    }
}
?>
