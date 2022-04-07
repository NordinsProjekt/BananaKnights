<?php
require_once "classes/PDOHandler.class.php";



class AuthorsModel extends PDOHandler
{


    public function GetAllAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname FROM authors");
        $stmt->execute();

        return $stmt->fetchAll(); 
    }


    public function GetAuthor($id)
    {
        $stmt = $this->Connect()->prepare(
        "SELECT Firstname, Lastname, Country, Born, Death 
        FROM authors
        WHERE Id = :id
        ");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }




}

?>