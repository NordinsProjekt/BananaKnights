<?php
require_once "classes/Base.Controller.class.php";
class moderatorController extends BaseController
{
    //private $db;

    function __construct()
    {
        //$this->db = new DefaultModel();
    }

    function __destruct()
    {
        
    }

    public function ShowModeratorPage()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            require_once "views/default.php";
            require_once "views/moderator.php";
            require_once "model/Authors.Model.php";
            require_once "model/Reviews.Model.php";
            require_once "model/Books.Model.php";
            require_once "model/Comments.Model.php";
            $authorDB = new AuthorsModel();
            $reviewDB = new ReviewsModel();
            $bookDB = new BooksModel();
            $commentDB = new CommentsModel();

            $formData['BannedComments'] = $commentDB->GetAllFlaggedComments();
            $formData['BannedReply'] = $commentDB->GetAllFlaggedReplies();
            $formData['BannedAuthors'] = $authorDB->GetAllFlaggedAuthors();
            $formData['BannedReviews'] = $reviewDB->GetAllFlaggedReviews();

            echo StartPage("Moderatorpanelen");
            IndexNav($user['Roles'],$user['Username']);
            echo ModeratorIndex($formData,$user['Roles']);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
        }
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