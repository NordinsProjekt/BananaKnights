<?php
require_once "classes/PDOHandler.class.php";
class StatsModel extends PDOHandler
{
    function __destruct()
    {

    }

    public function GetNumberOfBooks()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofBooks FROM books 
        WHERE IsDeleted = 0 AND Flagged = 0;");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function GetNumberOfAuthors()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofAuthors FROM authors 
        WHERE Flagged = 0;");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function GetNumberOfGenre()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofGenre FROM genres;");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function GetNumberOfUsers()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofUsers FROM users;");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function GetNumberOfReviews()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofReviews FROM reviews 
        WHERE Flagged = 0;");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function GetNumberOfComments()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofComments FROM comments
        WHERE Flagged = 0 AND IsDeleted = 0;");
        $stmt->execute();
        return $stmt->fetch(); 
    }
    public function GetNumberOfReplies()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberofReplies FROM replies 
        WHERE Flagged = 0 AND IsDeleted = 0");
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function UserWithMostComments()
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(comments.Id) AS NumberofComments, users.UserName FROM comments 
        INNER JOIN users ON comments.UserId = users.Id 
        WHERE Flagged = 0 AND IsDeleted = 0 GROUP BY UserId LIMIT 1;");
        $stmt->execute();
        return $stmt->fetch(); 
    }
}
?>