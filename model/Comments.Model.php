<?php
require_once "classes/PDOHandler.class.php";
class CommentsModel extends PDOHandler
{
    function __destruct()
    {
        
    }

    public function GetAllComments($reviewid)
    {
        $stmt = $this->Connect()->prepare("SELECT c.Id, ui.UserName, c.Comment, c.Created, c.Flagged, r.Id AS ReviewId
        FROM comments AS c
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN books AS b ON r.BookId = b.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE r.Id = :id AND c.Flagged = 0 AND c.IsDeleted = 0
        ORDER BY c.Created DESC;");
        $stmt->bindParam(":id",$reviewid);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllReplies()
    {
        $stmt = $this->Connect()->prepare(
            "SELECT r.Id AS ReplyId, c.Id AS CommentId, r.CommentId, r.Reply, r.Created, r.UserId, ui.UserName
            FROM replies AS r
            INNER JOIN comments AS c ON c.Id = r.CommentId
            INNER JOIN users AS ui ON c.UserId = ui.Id 
            WHERE r.Flagged = 0 AND r.IsDeleted = 0;");
            $stmt->execute();
            return $stmt->fetchAll(); 
    }

    public function InsertReply($arr)
    {
        $stmt = $this->Connect()->prepare(
        "INSERT INTO replies (CommentId,Reply,Created,UserId)
        VALUES (?,?,?,?);");
        return $stmt->execute($arr);
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
    public function FlagReply($replyId)
    {
        $stmt = $this->Connect()->prepare("UPDATE replies 
        SET Flagged = 1 WHERE Id = :id; ");
        $stmt->bindParam(":id",$replyId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UnFlagReply($replyId)
    {
        $stmt = $this->Connect()->prepare("UPDATE replies 
        SET Flagged = 0 WHERE Id = :id; ");
        $stmt->bindParam(":id",$replyId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function HideComment($commentId)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments 
        SET IsDeleted = 1 WHERE Id = :id; ");
        $stmt->bindParam(":id",$commentId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function ReviveComment($commentId)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments 
        SET IsDeleted = 0 WHERE Id = :id; ");
        $stmt->bindParam(":id",$commentId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function HideReplies($replyId)
    {
        $stmt = $this->Connect()->prepare("UPDATE replies 
        SET IsDeleted = 1 WHERE Id = :id; ");
        $stmt->bindParam(":id",$replyId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function ReviveReplies($replyId)
    {
        $stmt = $this->Connect()->prepare("UPDATE replies 
        SET IsDeleted = 0 WHERE Id = :id; ");
        $stmt->bindParam(":id",$replyId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UpdateComment($arr)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments SET Comment = ?
        WHERE Id = ?;");
        return $stmt->execute($arr);
    }

    public function UpdateReply($arr)
    {
        $stmt = $this->Connect()->prepare("UPDATE replies SET Reply = ?
        WHERE Id = ?;");
        return $stmt->execute($arr);
    }

    public function FlagComment($commentid)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments 
        SET Flagged = 1 WHERE Id = :id; ");
        $stmt->bindParam(":id",$commentid,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UnFlagComment($commentid)
    {
        $stmt = $this->Connect()->prepare("UPDATE comments 
        SET Flagged = 0 WHERE Id = :id; ");
        $stmt->bindParam(":id",$commentid,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function GetAllFlaggedComments()
    {
        $stmt = $this->Connect()->prepare("SELECT c.Id, ui.UserName, c.Comment AS Text, c.Created, r.Id AS ReviewId 
        FROM comments AS c
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE c.Flagged = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllFlaggedReplies()
    {
        $stmt = $this->Connect()->prepare("SELECT rep.Id, ui.UserName, rep.Reply AS Text, rep.Created, r.Id AS ReviewId 
        FROM replies AS rep
        INNER JOIN comments AS c ON rep.CommentId = c.Id 
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE rep.Flagged = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllDeletedComments()
    {
        $stmt = $this->Connect()->prepare("SELECT c.Id, ui.UserName, c.Comment AS Text, c.Created, r.Id AS ReviewId 
        FROM comments AS c
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE c.IsDeleted = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllDeletedReplies()
    {
        $stmt = $this->Connect()->prepare("SELECT rep.Id, ui.UserName, rep.Reply AS Text, rep.Created, r.Id AS ReviewId 
        FROM replies AS rep
        INNER JOIN comments AS c ON rep.CommentId = c.Id 
        INNER JOIN commentreviews AS cr ON c.Id  = cr.CommentId
        INNER JOIN reviews AS r ON cr.ReviewId = r.Id
        INNER JOIN users AS ui ON c.UserId = ui.Id
        WHERE rep.IsDeleted = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }


















}
?>