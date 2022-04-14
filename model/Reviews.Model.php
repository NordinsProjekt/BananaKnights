<?php
require_once "classes/PDOHandler.class.php";



class ReviewsModel extends PDOHandler
{


    public function InsertReview($inputArr)
    {
        $stmt = $this->Connect()->prepare(
            "INSERT INTO reviews (BookId, UserId, Title, Text, Rating, Created)
            VALUES (?,?,?,?,?,?)
            ");
        $stmt->execute($inputArr);
    }

    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT r.Title AS ReviewTitle,r.Text AS ReviewText,r.Rating,r.Created,u.UserName,b.Title AS BookTitle,
        b.PublicationYear AS BookYear, b.ImagePath AS BookImagePath FROM reviews AS r 
        INNER JOIN users AS u ON r.UserId = u.Id 
        INNER JOIN books AS b ON r.BookId = b.Id 
        WHERE b.IsDeleted = 0;");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>