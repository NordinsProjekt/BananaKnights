<?php
require_once "model/Reviews.Model.php";
require_once "classes/Base.Controller.class.php";
class ReviewsController extends BaseController
{

    private $db;

    function __construct()
    {
        $this->db = new ReviewsModel();
    }

    function NewReview()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"User"))
        {
            require_once "views/reviews.php";
            require_once "views/default.php";
            require_once "model/Books.Model.php";
            $bookDB = new BooksModel();
            $book = $bookDB->GetBook($this->CheckUserInputs($_POST['bookId']));
            $review = $this->db->CheckIfUserAlreadyMadeOne($user['Id'],$book['Id']);
            if ($book && $review['Antal']<1)
            {
                echo StartPage("Skriv recension");
                IndexNav("User",$user['Username']);
                echo AddNewReview($book);
                echo EndPage();
            }
            else
            {
                $this->ShowError("Du har redan skrivit en recension för denna boken");
            }

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
            if ($result)
            {
                require_once "controller/Home.Controller.php";
                $home = new HomeController();
                $home->ShowHomePage();
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

    public function ShowReview()
    {
        $role = "";
        $safe = $this->CheckUserInputs($_POST['id']);
        $result = $this->db->GetReview($safe);
        $user = $this->GetUserInformation();
        if ($result)
        {
            if (str_contains($user['Roles'],"User"))
            {
                $role = "User";
                if (str_contains($user['Roles'],"Admin"))
                {$role = "Admin";}
            }
            $usefull = $this->db->IsUsefullSet($safe,$user['Id']);
            if ($usefull['Antal'] == 1)
            {
                $result+= ["Usefull" => true];
            }
            else
            {
                $result+= ["Usefull" => false];
            }
            require_once "views/reviews.php";
            require_once "views/default.php";
            echo StartPage("Review");
            IndexNav($role,$user['Username']);

            echo ShowReview($result,$role);
            require_once "model/Comments.Model.php";
            require_once "views/comments.php";
            $comments = new CommentsModel();
            $comments = $comments->GetAllComments($result['Id']);
            if ($comments)
            {
                echo ShowAllComments($comments,$role);
            }
            echo EndPage();
        }
        else
        {
            $this->ShowError("Recensionen kunde inte hittas");
        }

        
    }

    public function ShowAllReviews()
    {
        //Endast admin ska få se denna viewn
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $result = $this->db->GetAll();
            if ($result)
            {
                require_once "views/reviews.php";
                require_once "views/default.php";
                echo StartPage("Alla reviews");
                IndexNav("Admin",$user['Username']);
                echo ShowAllReviews($result,"Admin");
                echo EndPage();
            }

        }
        else
        {
            $this->ShowError("Du har inte rättighet att se detta");
        }
    }
    public function WasUsefull()
    {
        if (isset($_SESSION['ReviewId']))
        {
            $user = $this->GetUserInformation();
            $safe = $this->CheckUserInputs($_POST['id']);
            if ($_SESSION['ReviewId'] == $safe)
            {
                unset($_SESSION['ReviewId']); //Raderar backupvärdet.
                $user = $this->GetUserInformation();
                $safe = $this->CheckUserInputs($_POST['id']);
                if (str_contains($user['Roles'],"User") && $safe > 0)
                {
                    //Kollar om det användaren har tryckt på knappen eller inte
                    $result = $this->db->IsUsefullSet($safe,$user['Id']);
                    if ($result['Antal'] == 1)
                    {
                        //Radera i databasen
                        $this->db->DeleteWasReviewUsefull($safe,$user['Id']);
                        $this->ShowReview();
                    }
                    else
                    {
                        //Lägg till i databasen
                        $this->db->SetWasReviewUsefull($safe,$user['Id']);
                        $this->ShowReview();
                    }
                }
                else
                {
                    $this->ShowError("Logga in om du vill använda denna funktionen");
                }
            }
            else
            {
                echo "Manipulerat Id från form. Bannad!!";
            }
        }
        else
        {
            $this->ShowError("Försök till forminjection");
            exit();
        }
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
}
?>