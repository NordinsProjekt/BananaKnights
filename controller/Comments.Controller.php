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
            if (str_contains($user['Roles']," "))
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