<?php
require_once "model/Comments.Model.php";
require_once "classes/Base.Controller.class.php";
class CommentsController extends BaseController
{
    function ShowComments()
    {
        $user = $this->GetUserInformation();
        $safetext = $this->ScrubInputs($_POST['id']);
        $result = $this->db->GetAllReviews($safetext);
        if ($result)
        {
            require_once "views/comments.php";
            echo ShowAllComments($result,$user['Roles']);
        }
        else
        {
            $this->ShowError("Boken finns inte");
        }
    } 



    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }



}
?>