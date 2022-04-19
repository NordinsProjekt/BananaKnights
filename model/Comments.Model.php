<?php
require_once "classes/PDOHandler.class.php";
class CommentsModel extends PDOHandler
{
    function __destruct()
    {
        
    }

    public function GetAllComments($reviewid)
    {
        $stmt = $this->Connect()->prepare("SELECT c.Id, ui.UserName, c.Comment, c.Created, c.Flagged
        FROM comments AS c
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN books AS b ON r.BookId = b.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE r.Id = :id AND c.Flagged = 0;");
        $stmt->bindParam(":id",$reviewid);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }


    public function InsertComment($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO comments (UserId,Comment,Created,Flagged)
        VALUES (?,?,?,?);");
        return $stmt->execute($arr);
    }

    public function InsertCommentReviews($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO commentreviews (CommentId,ReviewId)
        VALUES (?,?);");
        return $stmt->execute($arr);
    }

    public function GetCommentId($arr)
    {
        $stmt = $this->Connect()->prepare(
        "SELECT Id,UserId,Comment,Created,Flagged FROM comments 
        WHERE Comment = :comment");

        $stmt->bindParam(':comment', $arr[1], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function HideReview($commentid)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments 
        SET Flagged = 1 WHERE Id = :id; ");
        $stmt->bindParam(":id",$commentid,PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }



















}
?>