<?php
require_once "classes/PDOHandler.class.php";



class ReviewsModel extends PDOHandler
{
    public function CheckIfUserAlreadyMadeOne($userId,$bookId)
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS Antal FROM reviews 
        WHERE UserId = :userId AND BookId = :bookId AND IsDeleted = 0");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function InsertReview($inputArr)
    {
        $stmt = $this->Connect()->prepare(
            "INSERT INTO reviews (BookId, UserId, Title, Text, Rating, Created)
            VALUES (?,?,?,?,?,?)
            ");
        $stmt->execute($inputArr);
    }

    public function GetReview($id)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND r.Id = :id;");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0;");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function GetAllReviewsBook($bookId)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND b.Id = :bookId;");
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>