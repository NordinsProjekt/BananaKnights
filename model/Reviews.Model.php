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

}

?>