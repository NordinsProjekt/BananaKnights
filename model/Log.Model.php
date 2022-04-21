<?php
require_once "classes/PDOHandler.class.php";
class LogModel extends PDOHandler
{
    function __destruct()
    {
        
    }

    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM showerror_log");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function SetErrorMessage($errorArr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO showerror_log 
        (UserId,Class,Function,Message,UserIP,UserBrowser,Created) VALUES 
        (?,?,?,?,?,?,?);");
        return $stmt->execute($errorArr);
    }
}
?>