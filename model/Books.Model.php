<?php
require_once "classes/PDOHandler.class.php";
class BooksModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
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
}
?>