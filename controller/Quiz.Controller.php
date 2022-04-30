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