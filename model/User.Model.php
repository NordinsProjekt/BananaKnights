<?php
require_once "classes/PDOHandler.class.php";
class UserModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
    public function GetUser($arr)
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM users");
    }
}
?>