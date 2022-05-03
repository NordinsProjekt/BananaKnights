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
        $stmt = $this->Connect()->prepare("SELECT q.Id ,q.Title, u.UserName, q.Created FROM quiz AS q 
        INNER JOIN users AS u ON q.UserId = u.Id 
        WHERE q.IsDeleted = 0 AND q.Flagged = 0 AND q.BookId = :bookId AND q.Done = 1;");
        $stmt->bindParam(":bookId",$bookId,PDO::PARAM_INT);
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
}
?>