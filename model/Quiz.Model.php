<?php
require_once "classes/PDOHandler.class.php";
class QuizModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM tabelnamn");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
    
    public function GetAllQuizForBook($bookId)
    {
        $stmt = $this->Connect()->prepare("SELECT q.Id ,q.Title, u.UserName, q.Created, b.Title AS BookTitle,b.Id AS BookId FROM quiz AS q 
        INNER JOIN users AS u ON q.UserId = u.Id 
        INNER JOIN books AS b ON q.BookId = b.Id
        WHERE q.IsDeleted = 0 AND q.Flagged = 0 AND b.IsDeleted = 0 AND q.Done = 1 AND q.BookId = :bookId;");
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllQuiz()
    {
        $stmt = $this->Connect()->prepare("SELECT q.Id ,q.Title, u.UserName, q.Created, b.Title AS BookTitle FROM quiz AS q 
        INNER JOIN users AS u ON q.UserId = u.Id 
        INNER JOIN books AS b ON q.BookId = b.Id
        WHERE q.IsDeleted = 0 AND q.Flagged = 0 AND b.IsDeleted = 0 AND q.Done = 1;");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetQuestionsForQuiz($quizId)
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM questions WHERE QuizId = :quizId ORDER BY Id ASC");
        $stmt->bindParam(":quizId",$quizId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function CreateQuiz($quizArr)
    {
        $stmt = $this->Connect()->prepare("INSERT quiz (Title,UserId,BookId,Created,IsDeleted,Flagged,Done) VALUES 
        (?,?,?,?,?,?,?);");
        return $stmt->execute($quizArr);
    }

    public function GetQuizId($arr)
    {
        $stmt = $this->Connect()->prepare("SELECT Id FROM quiz WHERE 
        Title = ? AND UserId = ? AND BookId = ? AND Created = ? AND IsDeleted = ? AND Flagged = ? AND Done = ?;");
        $stmt->execute($arr);
        $result = $stmt->fetch();
        return $result['Id'];
    }

    public function InsertQuestions($arr)
    {
        //Sparar alla frågorna i databasen
        $stmt = $this->Connect()->prepare("INSERT INTO questions (Question,Alt1,Alt2,Alt3,Alt4,Answer,QuizId) 
        VALUES (?,?,?,?,?,?,?);");
        for ($i=0; $i < count($arr); $i++) 
        { 
            if ($stmt->execute($arr[$i]))
            {

            }
            else
            {
                return false;
            }
        }
        return true;
    }

    public function UpdateQuizDone($quizId)
    {
        $stmt = $this->Connect()->prepare("UPDATE quiz SET Done = 1 WHERE Id = :quizId");
        $stmt->bindParam(":quizId",$quizId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UpdateFlagQuiz($flag,$quizId)
    {
        $stmt = $this->Connect()->prepare("UPDATE quiz SET Flagged = :flag 
        WHERE Id = :quizId;");
        $stmt->bindParam(":flag",$flag,PDO::PARAM_INT);
        $stmt->bindParam(":quizId",$quizId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function GetAllFlaggedQuiz()
    {
        $stmt = $this->Connect()->prepare("SELECT q.Id,q.Title,u.UserName, b.Title AS BookTitle,b.Id AS BookId FROM quiz AS q
        INNER JOIN books AS b ON q.BookId = b.Id 
        INNER JOIN users AS u ON q.UserId = u.Id
        WHERE b.IsDeleted = 0 AND q.Flagged = 1 AND q.Done = 1;");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>