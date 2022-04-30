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

    public function CreateQuizForm()
    {
        $user = $this->GetUserInformation();
        //Användare som tillhör UserGruppen kan skapa Quiz
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin"))
        {
            $formData['User'] = $user;
            $formData['Book'] = array("Id"=>1);
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
                //Allt är ok.
                //(Title,UserId,BookId,EndDate,Link)
                $access = "";
                if ($_POST['access'] == "public" || $_POST['access'] = "private")
                {
                        if ($_POST['access'] == "private")
                        {
                            $access = $this->GenerateLink();
                        }
                }
                $arr = array(
                    $_POST['title'],$form['userId'],$form['bookId'],date("Y-m-d H:i:s"),$_POST['enddate'],0,0,$access
                );
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
        if (!$user['Roles'] == "")
        {
            $form = $_SESSION['form'][$_POST['formname']];
            for ($i=0; $i < $form['NumberOfQuestions']; $i++) 
            { 
                //Bygger en array för varje fråga
                $arr[] = array(
                    $_POST['question'][$i],$_POST['answer1'][$i],$_POST['answer2'][$i],
                    $_POST['answer3'][$i],$_POST['answer4'][$i],$_POST['realanswer'][$i]
                );
            }
            //Anropa databasen och modellen kommer inserta en rad för varje array och koppla till quizen
        }
    }

    public function EditQuiz($id)
    {

    }

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";"," ","/",",","<",">",")","(","=","[","]","+","*");
      $safe = str_replace($banlist,"",$notsafeText);
      return $safe;
    }

    //Mellanslag tillåtna
    private function CheckUserName($notsafeText)
    {
        $banlist = array("\t",".",";","/",",","<",">",")","(","=","[","]","+","*");
        $safe = str_replace($banlist,"",$notsafeText);
        return $safe;
    }
}
?>