<?php
require_once "model/Quiz.Model.php";
require_once "classes/Base.Controller.class.php";

class QuizController extends BaseController
{
    private $db;

    function __construct()
    {
        $this->db = new QuizModel();
    }

    function __destruct()
    {
        
    }

    public function ShowAll()
    {
        $user = $this->GetUserInformation();
        $result = $this->db->GetAllQuiz();
        require_once "views/default.php";
        require_once "views/quiz.php";
        echo StartPage("Alla Quiz");
        IndexNav($user['Roles'],$user['Username']);
        echo ShowAllQuizAllBooks($result);
        echo EndPage();
    }
    public function ShowQuiz()
    {
        $safeId = $this->ScrubIndexNumber($_POST['id']);
        $user = $this->GetUserInformation();
        $result = $this->db->GetQuestionsForQuiz($safeId);
        if ($result)
        {
            require_once "views/default.php";
            require_once "views/quiz.php";
            $formData['Quiz']['Id'] = $safeId;
            $formData['Quiz']['Questions'] = $result;
            echo StartPage("Quiztime");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowQuiz($formData,$user);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Finns inget quiz");
        }

    }

    public function CheckAnswers()
    {
        $user = $this->GetUserInformation();
        $quizId = $this->ScrubIndexNumber($_POST['QuizId']);
        $answers = $_POST;
        $result = $this->db->GetQuestionsForQuiz($quizId);
        $score = 0;
        for ($i=0; $i < count($result); $i++) 
        { 
            //Kollar om svaret är ett nummer mellan 1-4
            if ($this->ValidateAnswers($answers['answer'.$i]))
            {
                if ($result[$i]['Answer'] == $answers['answer'.$i])
                {
                    $score += 1;
                }
            }
            else
            {
                $this->ShowError("Felaktig data från användaren!!");
                exit();
            }

        }
        require_once "views/default.php";
        require_once "views/quiz.php";
        echo StartPage("Resultat");
        IndexNav($user['Roles'],$user['Username']);
        echo Winner("Du fick ".$score." av ".count($result)." rätt");
        echo EndPage();
    }

    public function CreateQuizForm()
    {
        $user = $this->GetUserInformation();
        //Användare som tillhör UserGruppen kan skapa Quiz
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin"))
        {
            $bookId = $this->ScrubIndexNumber($_POST['bookId']);
            $formData['User'] = $user;
            $formData['Book'] = array("Id"=>$bookId);
            require_once "views/default.php";
            require_once "views/quiz.php";
            echo StartPage("Skapa Quiz");
            IndexNav($user['Roles'],$user['Username']);
            echo QuizForm($formData);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du måste vara inloggad för att skapa ett Quiz!!");
        }
    }

    public function SaveQuizForm()
    {
        //TODO behöver validera alla inputs från formulären
        $user = $this->GetUserInformation();
        //Användare som tillhör UserGruppen kan skapa Quiz
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin"))
        {
            //Kontroll av formuläret, har rätt userId skickats från formuläret
            if( $_SESSION['form'][$this->ScrubFormName($_POST['formname'])]['userId'] == $user['Id'])
            {
                $form = $_SESSION['form'][$this->ScrubFormName($_POST['formname'])];
                unset($_SESSION['form']);
                //Allt är ok.
                $arr = array(
                    $_POST['title'],$form['userId'],$form['bookId'],date("Y-m-d H:i:s"),0,0,0
                );
                if ($_POST['antalQ'] <=0)
                {

                }
                $arr[0] = $this->ScrubVar($arr[0]);
                $result = $this->db->CreateQuiz($arr);
                if ($result)
                {
                    $id = $this->db->GetQuizId($arr);
                    if ($id > 0 && is_numeric($id))
                    {
                        $formData['User'] = $user;
                        $formData['QuizId'] = $id;
                        $formData['NumberOfQ'] = $_POST['antalQ'];
                        require_once "views/default.php";
                        require_once "views/quiz.php";
                        echo StartPage("Skapa Quiz");
                        IndexNav($user['Roles'],$user['Username']);
                        echo QuestionForm($formData);
                        echo EndPage();
                    }

                }

            }
            else
            {
                //Felanvändare har postat till denna route.
            }
        }
    }

    public function SaveQuestions()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin"))
        {
            $form = $_SESSION['form'][$_POST['formname']];
            $quizId = $form['QuizId'];
            for ($i=0; $i < $form['NumberOfQuestions']; $i++) 
            { 
                //Bygger en array för varje fråga
                $arr[] = array(
                    $_POST['question'][$i],$_POST['answer1'][$i],$_POST['answer2'][$i],
                    $_POST['answer3'][$i],$_POST['answer4'][$i],$_POST['realanswer'][$i],$quizId
                );
                $arr = $this->ScrubText($arr);
                //Kastar användaren till errorsidan och avbryter allt.
                if (!$this->ValidateArray($arr))
                {            
                    $this->ShowError("Felaktig data från användaren!!");
                    exit();
                }
            }
            //Anropa databasen och modellen kommer inserta en rad för varje array och koppla till quizen
            $result = $this->db->InsertQuestions($arr);
            if ($result)
            {
                $this->db->UpdateQuizDone($quizId);
                require_once "controller/Home.Controller.php";
                $homeController = new HomeController();
                $homeController->ShowHomePage();
            }
            else
            {
                $this->ShowError("Något gick fel med att spara Quizet!");
            }
        }
    }

    public function EditQuiz($id)
    {

    }

    //Återställer från flaggat tillstånd
    public function UnFlagQuiz()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $formName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$formName]['QuizId']);
            $bookId = $_SESSION['form'][$formName]['BookId'];
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagQuiz(0,$safe);
            if ($result)
            {
                require_once "controller/Books.Controller.php";
                $bookController = new BooksController();
                $bookController->ShowBook($bookId);
            }
            else
            {
                $this->ShowError("Något gick fel med att återställa författaren");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    //Flaggar för kontroll
    public function FlagQuiz()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $formName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$formName]['QuizId']);
            $bookId = $_SESSION['form'][$formName]['BookId'];
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagQuiz(1,$safe);
            if ($result)
            {
                require_once "controller/Books.Controller.php";
                $bookController = new BooksController();
                $bookController->ShowBook($bookId);
            }
            else
            {
                $this->ShowError("Något gick fel med att flagga innehållet");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    private function ValidateArray($arr)
    {
        foreach ($arr as $key => $value) 
        {
            if ($value == NULL || $value = "")
            {
                return false;
            }
        }
        return true;
    }

    private function ValidateAnswers($answer)
    {
        if (is_numeric($answer) && $answer >0 && $answer <= 4)
        {
            return true;
        }
        return false;
    }
    
    //Mellanslag, comma och punkt tillåtna
    private function ScrubText($arr)
    {
        $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*");
        $safeArr = array();
        foreach($arr as $key => $value) 
        {
            $safe = str_replace($banlist,"",$value);
            $safeArr[] = $safe;
        }
        return $safeArr;
    }
    //Mellanslag, comma och punkt tillåtna
    private function ScrubVar($notsafe)
    {
        $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*");
        $safe = str_replace($banlist,"",$notsafe);
        return $safe;
    }
}
?>