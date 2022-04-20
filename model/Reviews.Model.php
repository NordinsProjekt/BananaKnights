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
            "INSERT INTO reviews (BookId, UserId, Title, Text, Rating, IsDeleted, Created)
            VALUES (?,?,?,?,?,?,?);
            ");
        return $stmt->execute($inputArr);
    }

    public function GetReview($id)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, b.Id AS BookId, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath, r.Flagged FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND r.Id = :id;");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetAllFlaggedReviews()
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND r.Flagged = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND r.Flagged = 0;");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function GetAllReviewsBook($bookId)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0 AND b.Flagged = 0 AND b.Id = :bookId AND r.IsDeleted = 0 AND r.Flagged = 0;");
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function GetAllReviewsSearch($searchInput)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id, r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE (b.IsDeleted = 0) AND (r.Title LIKE :title)");
        $stmt->bindParam(":title",$searchInput,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function SetWasReviewUsefull($reviewId,$userId)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO reviewusefull 
        (UserId,ReviewId) VALUES (:userId,:reviewId);");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":reviewId",$reviewId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function DeleteWasReviewUsefull($reviewId,$userId)
    {
        $stmt = $this->Connect()->prepare("DELETE FROM reviewusefull WHERE 
        UserId = :userId AND ReviewId = :reviewId;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":reviewId",$reviewId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();

    }

    public function IsUsefullSet($reviewId,$userId)
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(*) AS Antal FROM reviewusefull 
        WHERE UserId = :userId AND ReviewId = :reviewId;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam(":reviewId",$reviewId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function UpdateFlagReview($flag,$reviewId)
    {
        $stmt = $this->Connect()->prepare("UPDATE reviews SET Flagged = :flag 
        WHERE Id = :reviewId;");
        $stmt->bindParam(":flag",$flag,PDO::PARAM_INT);
        $stmt->bindParam(":reviewId",$reviewId,PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>