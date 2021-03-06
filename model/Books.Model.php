<?php
require_once "classes/PDOHandler.class.php";
class BooksModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    
    public function GetBook($id)
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,b.PublicationYear, b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, a.Id as AuthorId,g.Id AS GenreId, b.ISBN,b.ImagePath,b.Created FROM books AS b 
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

    public function GetAllDeletedBooks()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
         b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, b.Created, b.ImagePath FROM books AS b 
        
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.IsDeleted = 1
        ORDER BY b.Title ASC;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooks()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
         b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, b.Created, b.ImagePath FROM books AS b 
        
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.IsDeleted = 0
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
        
        return $stmt->execute($arr);
    }
    public function AddGenreToBook($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO genrebooks (GenreId,BookId) 
        VALUES (?,?);");
        
        return $stmt->execute($arr);
    }
    
    public function UpdateAuthorBook($authorId,$bookId)
    {
        $stmt = $this->Connect()->prepare("UPDATE bookauthors SET AuthorId = ? WHERE BookId = ?;");
        return $stmt->execute(array($authorId,$bookId));
    }

    public function UpdateGenreBooks($bookId,$genreId)
    {
        $stmt = $this->Connect()->prepare("UPDATE genrebooks SET GenreId = ? WHERE BookId = ?;");
        return $stmt->execute(array($genreId,$bookId));
    }

    public function UpdateBook($arr)
    {
        $stmt = $this->Connect()->prepare("UPDATE books SET Title = ?,
        PublicationYear = ?, Description = ?, ISBN = ?, ImagePath = ? WHERE Id = ?;");
        return $stmt->execute($arr);
    }

    public function HideBook($id)
    {
        $stmt = $this->Connect()->prepare("UPDATE books SET IsDeleted = 1 WHERE Id = :id ");
        $stmt->bindParam(":id",$id);
        return $stmt->execute();
    }

    public function ReviveBook($id)
    {
        $stmt = $this->Connect()->prepare("UPDATE books SET IsDeleted = 0 WHERE Id = :id ");
        $stmt->bindParam(":id",$id);
        return $stmt->execute();
    }
    public function SetGenre($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO genres (Name,Description,Created) 
        VALUES (?,?,?);");
        
        return $stmt->execute($arr);
    }
    public function DeleteGenre($genreId)
    {
        //Delete eller hide, det ??r fr??gan
        $stmt = $this->Connect()->prepare("DELETE FROM genres WHERE Id = :id;");
        $stmt->bindParam(":id",$genreId);
        $result = $stmt->execute();
        return $result;
    }

    public function HideGenre($genreId)
    {
        //Delete eller hide, det ??r fr??gan
        $stmt = $this->Connect()->prepare("UPDATE genres SET IsDeleted = 1 WHERE Id = :id;");
        $stmt->bindParam(":id",$genreId);
        $result = $stmt->execute();
        return $result;
    }

    public function ReviveGenre($genreId)
    {
        //Delete eller hide, det ??r fr??gan
        $stmt = $this->Connect()->prepare("UPDATE genres SET IsDeleted = 0 WHERE Id = :id;");
        $stmt->bindParam(":id",$genreId);
        $result = $stmt->execute();
        return $result;
    }

    public function GetGenre($genreId)
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Name, Description, Created FROM genres 
        WHERE IsDeleted = 0 AND Flagged = 0 AND Id = :genreId;");
        $stmt->bindParam(":genreId",$genreId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function UpdateGenre($id,$name,$description)
    {
        $stmt = $this->Connect()->prepare("UPDATE genres SET Name = ?, Description = ?
        WHERE Id = ?;");
        return $stmt->execute(array($name,$description,$id));
    }

    public function GetAllGenres()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Name, Description, Created FROM genres 
        WHERE IsDeleted = 0 AND Flagged = 0;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllDeletedGenre()
    {
        $stmt = $this->Connect()->prepare("SELECT Id, Name, Description, Created FROM genres 
        WHERE IsDeleted = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksSorted()
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id,b.Title,b.Description, a.Firstname, a.Lastname,
            IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
            b.ISBN,b.Created,b.ImagePath,IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE b.IsDeleted = 0 AND b.Flagged = 0
            ORDER BY Created DESC
            LIMIT 7;
            ");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksSortedByTitle($genreId)
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id,b.Title,b.Description,
            IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE b.IsDeleted = 0 AND b.Flagged = 0 AND g.Id = :genreId 
            ORDER BY b.Title ASC;
            ");
        $stmt->bindParam(":genreId",$genreId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksSearch($searchinput)
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id, b.Title,
            IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
             b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
             IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE (b.Title LIKE :input ) OR (IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) LIKE :input) OR (IF(g.IsDeleted=1,'n/a',g.Name) LIKE :input) AND (b.IsDeleted = 0)
            ");
        $stmt->bindParam(":input", $searchinput, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksAuthorSearch($searchinput)
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id, b.Title,
            IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
             b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
             IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE (IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) LIKE :input) AND (b.IsDeleted = 0)
            ");
        $stmt->bindParam(":input", $searchinput, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllBooksGenreSearch($searchinput)
    {
        $stmt = $this->Connect()->prepare(
            "SELECT b.Id, b.Title,
            IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
             b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
             IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName
            FROM books AS b
            INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
            INNER JOIN genres AS g ON g.Id = gb.GenreId
            INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
            INNER JOIN authors AS a ON a.Id = ba.AuthorId 
            WHERE (IF(g.IsDeleted=1,'n/a',g.Name) LIKE :input) AND (b.IsDeleted = 0)
            ");
        $stmt->bindParam(":input", $searchinput, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetBookAVGRatingTop5()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
         b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, b.Created, b.ImagePath ,AVG(Rating) AS Rating FROM books AS b 
        INNER JOIN reviews AS r ON b.Id = r.BookId 
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.IsDeleted = 0 AND b.Flagged = 0
        GROUP BY Id
        ORDER BY Rating DESC LIMIT 5;");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function GetBookAVGRatingLowest5()
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
         b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, b.Created, b.ImagePath ,AVG(Rating) AS Rating FROM books AS b 
        INNER JOIN reviews AS r ON b.Id = r.BookId 
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        WHERE b.IsDeleted = 0 AND b.Flagged = 0
        GROUP BY Id
        ORDER BY Rating ASC LIMIT 5;");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function IsRecommendedSet($bookId,$userId)
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(*) AS Antal FROM recommendbook 
        WHERE UserId = :userId AND BookId = :bookId;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function SetRecommendBook($bookId,$userId)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO recommendbook 
        (UserId,BookId) VALUES (:userId,:bookId);");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function DeleteRecommendBook($bookId,$userId)
    {
        $stmt = $this->Connect()->prepare("DELETE FROM recommendbook WHERE 
        UserId = :userId AND BookId = :bookId;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetAllRecommendedBooksByUser($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT b.Id, b.Title,
        IF(b.PublicationYear IS NULL or b.PublicationYear = '','n/a', b.PublicationYear) AS PublicationYear,
         b.Description, IF(g.IsDeleted=1,'n/a',g.Name) AS GenreName, 
        IF(a.IsDeleted=1,'n/a',CONCAT(a.Firstname, ' ', a.Lastname)) AS AuthorName, b.Created, b.ImagePath FROM books AS b 
        INNER JOIN genrebooks AS gb ON b.Id = gb.BookId 
        INNER JOIN genres AS g ON g.Id = gb.GenreId
        INNER JOIN bookauthors AS ba ON b.Id = ba.BookId 
        INNER JOIN authors AS a ON a.Id = ba.AuthorId 
        INNER JOIN recommendbook AS rb ON b.Id = rb.BookId 
        WHERE rb.UserId = :userId AND b.IsDeleted = 0 AND b.Flagged = 0
        ORDER BY b.Title;");
        $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>