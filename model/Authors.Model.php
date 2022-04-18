<?php
require_once "classes/PDOHandler.class.php";



class AuthorsModel extends PDOHandler
{
    public function GetAllAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname FROM authors WHERE Flagged = 0;");
        $stmt->execute();

        return $stmt->fetchAll(); 
    }

    public function GetAllFlaggedAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname FROM authors WHERE Flagged = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAuthor($id)
    {
        $stmt = $this->Connect()->prepare(
        "SELECT Id, Firstname, Lastname, Country, Born, Death, Flagged 
        FROM authors
        WHERE Id = :id
        ");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function InsertAuthor($inputArr)
    {
        $stmt = $this->Connect()->prepare(
            "INSERT INTO authors (Firstname, Lastname, Country, Created, Born, Death)
            VALUES (?,?,?,?,?,?)
            ");
        $stmt->execute($inputArr);
    }

    public function UpdateFlagAuthor($flag,$authorId)
    {
        $stmt = $this->Connect()->prepare("UPDATE authors SET Flagged = :flag 
        WHERE Id = :authorId;");
        $stmt->bindParam(":flag",$flag,PDO::PARAM_INT);
        $stmt->bindParam(":authorId",$authorId,PDO::PARAM_INT);
        return $stmt->execute();
    }



}

?>