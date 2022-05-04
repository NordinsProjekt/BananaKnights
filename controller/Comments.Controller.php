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

            if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin") || str_contains($user['Roles'],"Moderator") )
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
                        header("Location: ".prefix."showreview?id=".$safeReviewId);
                    }
                }
                else
                {
                    $this->ShowError("Något gick snett!");
                }
            }
            else
            {
                $this->ShowError("Du måste vara inloggad för att kommentera");
            }

    }

    function AddReply($commentId, $reviewId)
    {
        unset($_SESSION['ReviewId']);
        $safeCommentId = $this->ScrubIndexNumber($commentId);
        $user = $this->GetUserInformation();
        $safereply = $this->ScrubInputs($_POST['reply']);
        $safeReviewId = $this->ScrubIndexNumber($reviewId);
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
    
                $result = $this->db->InsertReply($cleanArr);
                if ($result)
                {
                    header("Location: ".prefix."showreview?id=".$safeReviewId);
                }
                else
                {
                    $this->ShowError("Något gick snett!");
                }
        }

    }
    public function FlagComment($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            require_once "controller/Reviews.Controller.php";
            $controllerReview = new ReviewsController();
            $result = $this->db->FlagComment($safe);
            if ($result)
            {
                //Behöver veta id för review
                $controllerReview->ShowReview($safeReviewId);
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function UnFlagComment($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator")) 
        {
            require_once "controller/Reviews.Controller.php";
            $controllerReview = new ReviewsController();
            $result = $this->db->UnFlagComment($safe);
            if ($result)
            {
                //Behöver veta id för review
                $controllerReview->ShowReview($safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte återställa kommentaren");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function FlagReply($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            require_once "controller/Reviews.Controller.php";
            $controllerReview = new ReviewsController();
            $result = $this->db->FlagReply($safe);
            if ($result)
            {
                //Behöver veta id för review
                $controllerReview->ShowReview($safeReviewId);
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function UnFlagReply($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator")) 
        {
            $result = $this->db->UnFlagReply($safe);
            if ($result)
            {
                //Behöver veta id för review
                header("Location:".prefix."showreview?id=".$safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte återställa kommentaren");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function UnDeleteComment($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin")) 
        {
            require_once "controller/Reviews.Controller.php";
            $controllerReview = new ReviewsController();
            $result = $this->db->ReviveComment($safe);
            if ($result)
            {
                //Behöver veta id för review
                $controllerReview->ShowReview($safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte återställa kommentaren");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function DeleteComment($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin")) 
        {
            $result = $this->db->HideComment($safe);
            if ($result)
            {
                //Behöver veta id för review
                header("Location:".prefix."showreview?id=".$safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte radera kommentaren");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function DeleteReply($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin")) 
        {
            $result = $this->db->HideReplies($safe);
            if ($result)
            {
                //Behöver veta id för review
                header("Location:".prefix."showreview?id=".$safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte radera svaret");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function UnDeleteReply($id)
    {
        $safeReviewId = $_POST['ReviewId'];
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin")) 
        {
            require_once "controller/Reviews.Controller.php";
            $controllerReview = new ReviewsController();
            $result = $this->db->ReviveReplies($safe);
            if ($result)
            {
                //Behöver veta id för review
                $controllerReview->ShowReview($safeReviewId);
            }
            else
            {
                $this->ShowError("Kunde inte återställa kommentaren");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function EditComment($id)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safeId = $this->ScrubIndexNumber($id);
            $reviewId = $_SESSION['ReviewId'];
            unset($_SESSION['ReviewId']);
            $arr = array(
                $_POST['reply'],$safeId
            );
            $arr = $this->ScrubText($arr);
            if ($this->ValidateArray($arr))
            {
                $this->db->UpdateComment($arr);
                require_once "controller/Reviews.Controller.php";
                $reviewController = new ReviewsController();
                $reviewController->ShowReview($reviewId);
            }
            else
            {
                $this->ShowError("Data gick inte att validera!!");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
    }

    public function EditReply($id)
    {
        var_dump($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safeId = $this->ScrubIndexNumber($id);
            $reviewId = $_SESSION['ReviewId'];
            unset($_SESSION['ReviewId']);
            $arr = array(
                $_POST['reply'],$safeId
            );
            $arr = $this->ScrubText($arr);
            if ($this->ValidateArray($arr))
            {
                $this->db->UpdateReply($arr);
                require_once "controller/Reviews.Controller.php";
                $reviewController = new ReviewsController();
                $reviewController->ShowReview($reviewId);
            }
            else
            {
                $this->ShowError("Data gick inte att validera!!");
            }
            
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
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

}
?>