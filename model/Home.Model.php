<?php
require_once "classes/PDOHandler.class.php";
class HomeModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM tabelnamn");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function AdminModeON()
    {
        $stmt = $this->Connect()->prepare("UPDATE sitesettings SET AdminMode = 1;");
        return $stmt->execute();
    }
}
?>