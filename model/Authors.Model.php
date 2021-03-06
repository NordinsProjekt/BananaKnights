<?php
require_once "classes/PDOHandler.class.php";



class AuthorsModel extends PDOHandler
{
    public function GetAllAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname FROM authors WHERE Flagged = 0 AND IsDeleted = 0;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllFlaggedAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname FROM authors WHERE Flagged = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllDeletedAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Firstname, Lastname, Country, Created FROM authors WHERE IsDeleted = 1;");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function GetAuthor($id)
    {
        $stmt = $this->Connect()->prepare(
        "SELECT Id, Firstname, Lastname, Country, Born, Death, Flagged, ImagePath 
        FROM authors
        WHERE Id = :id AND Flagged = 0 AND IsDeleted = 0;");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function InsertAuthor($inputArr)
    {
        $stmt = $this->Connect()->prepare(
            "INSERT INTO authors (Firstname, Lastname, Country, Created, Born, Death, ImagePath)
            VALUES (?,?,?,?,?,?,?)
            ");
        return $stmt->execute($inputArr);
    }

    public function UpdateAuthor($authorObj)
    {
        $stmt = $this->Connect()->prepare("UPDATE authors SET Firstname = ?, Lastname = ?, 
        Country = ?, Born = ?, Death = ?, ImagePath = ? WHERE Id = ?;");
        return $stmt->execute($authorObj);
    }

    public function UpdateFlagAuthor($flag,$authorId)
    {
        $stmt = $this->Connect()->prepare("UPDATE authors SET Flagged = :flag 
        WHERE Id = :authorId;");
        $stmt->bindParam(":flag",$flag,PDO::PARAM_INT);
        $stmt->bindParam(":authorId",$authorId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UpdateIsDeletedAuthor($delete,$authorId)
    {
        $stmt = $this->Connect()->prepare("UPDATE authors SET IsDeleted = :delete
        WHERE Id = :authorId;");
        $stmt->bindParam(":delete",$delete,PDO::PARAM_INT);
        $stmt->bindParam(":authorId",$authorId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function GetAllBooksSortedYear($authorId)
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear, 
        g.Name AS GenreName, 
        b.Created, b.ImagePath FROM books AS b 
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.IsDeleted = 0 AND a.Id = :authorId 
        ORDER BY b.PublicationYear DESC;");
        $stmt->bindParam(":authorId",$authorId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

}

?>