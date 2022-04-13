<?php
require_once "classes/PDOHandler.class.php";
class BooksModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetBook($id)
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,b.PublicationYear, b.Description, g.Name AS GenreName, 
        CONCAT(a.Firstname, ' ', a.Lastname) AS AuthorName, b.ISBN,b.ImagePath,b.Created FROM books AS b 
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.Id = :id AND b.IsDeleted = 0
        ORDER BY b.Title ASC;");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetAllBooks()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,b.PublicationYear, b.Description, g.Name AS GenreName, 
        CONCAT(a.Firstname, ' ', a.Lastname) AS AuthorName FROM books AS b 
        
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE IsDeleted = 0 
        ORDER BY b.Title ASC;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
    //Sparar en bok i databasen
    public function SetBook($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO books (UserId,Title,PublicationYear,Description,ISBN,ImagePath,IsDeleted,Created)
        VALUES (?,?,?,?,?,?,?,?);");
        $stmt->execute($arr);
        $stmt = $this->Connect()->prepare("SELECT Id FROM books WHERE ISBN = :isbn AND Title = :title AND PublicationYear = :year;");
        $stmt->bindParam(":isbn", $arr[4]);
        $stmt->bindParam(":title",$arr[1]);
        $stmt->bindParam(":year",$arr[2]);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function AddAuthorToBook($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO bookauthors (BookId,AuthorId) 
        VALUES (?,?);");
        $stmt->execute($arr);
        return true;
    }
    public function AddGenreToBook($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO genrebooks (GenreId,BookId) 
        VALUES (?,?);");
        $stmt->execute($arr);
        return true;
    }

    public function UpdateBook($arr)
    {

    }

    public function HideBook($id)
    {
        $stmt = $this->Connect()->prepare("UPDATE books SET IsDeleted = 1 WHERE Id = :id ");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return true;
    }
    public function SetGenre($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO genres (Name,Description,Created) 
        VALUES (?,?,?);");
        $stmt->execute($arr);
        return true;
    }
    public function DeleteGenre($genreId)
    {
        //Delete eller hide, det är frågan
        $stmt = $this->Connect()->prepare("DELETE FROM genres WHERE Id = :id;");
        $stmt->bindParam(":id",$genreId);
        $result = $stmt->execute();
        return $result;
    }
    public function GetAllGenres()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Name, Description, Created FROM genres");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksSorted()
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id,b.Title,b.Description,b.PublicationYear,b.ISBN,b.Created,b.ImagePath,g.Name
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            ORDER BY Created DESC
            LIMIT 8
            ");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksSearch($searchinput)
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id, b.Title,b.PublicationYear, b.Description, g.Name AS GenreName, 
            CONCAT(a.Firstname, ' ', a.Lastname) AS AuthorName
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE (b.Title LIKE :title) AND (b.IsDeleted = 0)
            ");
        $stmt->bindParam(":title", $searchinput, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
}
?>