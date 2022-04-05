<?php
require_once "classes/PDOHandler.class.php";
class BooksModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetBook($id)
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title, b.Description, g.Name AS GenreName, 
        CONCAT(a.Firstname, ' ', a.Lastname) AS AuthorName FROM books AS b 
        
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.Id = :id
        ORDER BY b.Title ASC;");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetAllBooks()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title, b.Description, g.Name AS GenreName, 
        CONCAT(a.Firstname, ' ', a.Lastname) AS AuthorName FROM books AS b 
        
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        ORDER BY b.Title ASC;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function SetBook($arr)
    {

    }

    public function UpdateBook($arr)
    {

    }

    public function HideBook($id)
    {
        
    }
}
?>