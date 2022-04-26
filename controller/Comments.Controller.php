<?php
require_once "model/Comments.Model.php";
require_once "classes/Base.Controller.class.php";
class CommentsController extends BaseController
{
    private $db;

    function __construct()
    {
        $this->db = new CommentsModel();
    }

    function AddComment($reviewId)
    {
            $safeReviewId = $this->ScrubIndexNumber($reviewId);
            $user = $this->GetUserInformation();
            $safetext = $this->ScrubInputs($_POST['Comment']);
            if (str_contains($user['Roles'],""))
            {
                $this->ShowError("Du måste vara inloggad för att kommentera");
            }
            else
            {
                $inputArr = array (
                    $user['Id'], $safetext,
                    date("Y-m-d h:i:s"), "0"
                );
                $cleanArr = $this->ScrubSaveArr($inputArr);
        
                $result = $this->db->InsertComment($cleanArr);
                if ($result)
                {
                    $test = $this->db->GetCommentId($cleanArr);
                    $inputArr = array($test['Id'],$reviewId);
                    $result = $this->db->InsertCommentReviews($inputArr);
                    if($result)
                    {
                        //Lite hackigt men det funkar
                        require_once "controller/Reviews.Controller.php";
                        $reviewDB = new ReviewsController();
                        $_POST['id'] = $safeReviewId;
                        $reviewDB->ShowReview();
                    }
                }
                else
                {
                    $this->ShowError("Något gick snett!");
                }
            }
    }

    function AddReply($commentId, $reviewId)
    {
        $safeCommentId = $this->ScrubIndexNumber($commentId);
        $user = $this->GetUserInformation();
        $safereply = $this->ScrubInputs($_POST['reply']);
        if (str_contains($user['Roles']," "))
        {
            $this->ShowError("Du måste vara inloggad för att svara på kommentarer");
        }
        else
        {
            $inputArr = array (
                $safeCommentId, $safereply, 
                date("Y-m-d h:i:s"), $user['Id']
            );
            $cleanArr = $this->ScrubSaveArr($inputArr);
    
            if($_SESSION['ReviewId'] == " ")
            {
                /* Skickar tillbaka användaren till homepage om hen försöker refresha sidan*/
                /* Annars så läggs en likadan kommentar till i db */
                require_once "controller/Home.Controller.php";
                $controller = new HomeController();
                $controller->ShowHomePage();
                $message = "Nice try :)";
                echo "<script type='text/javascript'>alert('$message');</script>";

            }
            else
            {
                $result = $this->db->InsertReply($cleanArr);
                if ($result)
                {
                        require_once "controller/Reviews.Controller.php";
                        $reviewDB = new ReviewsController();
                        $_POST['id'] = $reviewId;
                        $reviewDB->ShowReview();
                        $_SESSION['ReviewId'] = " ";
                }
                else
                {
                    $this->ShowError("Något gick snett!");
                }
            }
        }

    }


    private function ScrubSaveArr($arr)
    {
        $cleanArr = array();
        for ($i=0; $i < count($arr); $i++) { 
            $cleanArr[] = $this->CheckUserInputs($arr[$i]);
        }
        return $cleanArr;
    }

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }

}
?>