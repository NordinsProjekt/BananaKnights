<?php
require_once "classes/PDOHandler.class.php";
class BooksModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM books");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
}
?>