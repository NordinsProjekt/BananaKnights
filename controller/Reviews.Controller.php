<?php
require_once "model/Reviews.Model.php";
class ReviewsController
{

    private $db;

    function __construct()
    {
        $this->db = new ReviewsModel();
    }

    function NewReview()
    {
        if ($this->VerifyUserRole("User"))
        {
            require_once "views/reviews.php";
            require_once "views/default.php";
            require_once "model/Books.Model.php";
            $bookDB = new BooksModel();
            $book = $bookDB->GetBook($_POST['bookId']);
            $page = "";
            $page .= StartPage("Skapa ny Review");
            $page .= AddNewReview($book);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Du måste vara inloggad för att skriva reviews");
        }
    }

    function AddReview()
    {
        if ($this->VerifyUserRole("User"))
        {
            $arr = array (
                $_POST['id'], $_SESSION['UserId'], $_POST['Title'],
                $_POST['Text'], $_POST['Rating'], date("Y-m-d h:i:s")
            );
    
            $cleanArr = $this->ScrubSaveAuthorArr($arr);
    
            $result = $this->db->InsertReview($cleanArr);
            if (!$result)
            {
                echo "Review lades till på boken";
            }
            else
            {
                $this->ShowError("Något gick snett i formuläret!");
            }
        }
        else
        {
            $this->ShowError("Du måste vara inloggad");
            exit();
        }
    }

    public function ShowAllReviews()
    {
        
    }


    private function ScrubSaveAuthorArr($arr)
    {
        $cleanArr = array();
        for ($i=0; $i < count($arr); $i++) { 
            $cleanArr[] = $this->CheckUserInputs($arr[$i]);
        }
        return $cleanArr;
    }

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }

    public function ShowError($errorText)
    {
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Fel vid inläsning");
        $page .= "<h1>FEL</h1><p>" . $errorText . "</p>";
        $page .= EndPage();
        echo $page;
    }

    private function VerifyUserRole($roleName)
    {
        if (isset($_SESSION['is_logged_in']) && isset($_SESSION['UserId']))
        {
            if ($_SESSION['is_logged_in'] === true && $_SESSION['UserId']>0)
            {
                require_once "model/User.Model.php";
                $userDB = new UserModel();
                if ($userDB->DoesUserHaveRole($roleName,$_SESSION['UserId']) == 1)
                {
                    return true;
                }
            }
        }
        return false;
    }

}
?>