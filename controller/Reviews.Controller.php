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
                IndexNav($user['Roles'],$user['Username']);
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
                $_POST['Text'], $_POST['Rating'], "0", date("Y-m-d H:i:s")
            );
    
            $cleanArr = $this->ScrubSaveAuthorArr($arr);
            //$cleanArr = $this->ConvertEnterKey($cleanArr[3]);
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

    public function ShowReview($id)
    {
        $safe = $this->CheckUserInputs($id);
        $result = $this->db->GetReview($safe);
        $user = $this->GetUserInformation();
        if ($result)
        {
            if ($user['Roles'] != "")
            {
                $usefull = $this->db->IsUsefullSet($safe,$user['Id']);
                if ($usefull['Antal'] == 1)
                {
                    $result+= ["Usefull" => true];
                }
                else
                {
                    $result+= ["Usefull" => false];
                }
            }
            require_once "views/reviews.php";
            require_once "views/default.php";
            echo StartPage("Review");
            IndexNav($user['Roles'],$user['Username']);

            echo nl2br(ShowReview($result,$user)); //nl2br ersätter \n (newline) med br
            require_once "model/Comments.Model.php";
            require_once "views/comments.php";
            $comments = new CommentsModel();
            $comments = $comments->GetAllComments($result['Id']);

            $replies = new CommentsModel();
            $replies = $replies->GetAllReplies();
            
            if ($comments)
            {
                echo CreateNewComment($result);
                echo ShowAllCommentsReplies($comments,$replies,$user['Roles']);
            }
            else
            {
                echo CreateNewComment($result);
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
        if (str_contains($user['Roles'],"Admin") ||str_contains($user['Roles'],"Moderator") )
        {
            $result = $this->db->GetAll();
            if ($result)
            {
                require_once "views/reviews.php";
                require_once "views/default.php";
                echo StartPage("Alla reviews");
                IndexNav($user['Roles'],$user['Username']);
                if(str_contains($user['Roles'],"Moderator"))
                {
                    echo SearchReview();
                }
                echo ShowAllReviews($result,$user['Roles']);
                echo EndPage();
            }
            else
            {
                require_once "views/reviews.php";
                require_once "views/default.php";
                echo StartPage("Alla reviews");
                IndexNav($user['Roles'],$user['Username']);
                if(str_contains($user['Roles'],"Moderator"))
                {
                    echo SearchReview();
                }
                echo ShowAllReviews($result,$user['Roles']);
                echo EndPage();
            }

        }
        else
        {
            $this->ShowError("Du har inte rättighet att se detta");
        }
    }

    function ShowSearchReview($searchinput)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin") ||str_contains($user['Roles'],"Moderator") )
        {
            $result = $this->db->GetAllReviewsSearch($searchinput);
            if ($result)
            {
                require_once "views/reviews.php";
                require_once "views/default.php";
                echo StartPage("Alla reviews");
                IndexNav($user['Roles'],$user['Username']);
                echo SearchReview();
                echo ShowAllReviews($result,$user['Roles']);
                echo EndPage();
            }
            else
            {
                require_once "views/reviews.php";
                require_once "views/default.php";
                echo StartPage("Alla reviews");
                IndexNav($user['Roles'],$user['Username']);
                echo SearchReview();
                echo "<h2>Fanns inga recensioner som matchar sökningen</h2>";
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
                if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Moderator") || str_contains($user['Roles'],"Admin") && $safe > 0)
                {
                    //Kollar om det användaren har tryckt på knappen eller inte
                    $result = $this->db->IsUsefullSet($safe,$user['Id']);
                    if ($result['Antal'] == 1)
                    {
                        //Radera i databasen
                        $this->db->DeleteWasReviewUsefull($safe,$user['Id']);
                        $this->ShowReview($safe);
                    }
                    else
                    {
                        //Lägg till i databasen
                        $this->db->SetWasReviewUsefull($safe,$user['Id']);
                        $this->ShowReview($safe);
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

    //Återställer från flaggat tillstånd
    public function UnFlagReview()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $fornName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$fornName]['reviewId']);
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagReview(0,$safe);
            if ($result)
            {
                $this->ShowAllReviews();
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
    public function FlagReview()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $formName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$formName]['reviewId']);
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagReview(1,$safe);
            if ($result)
            {
                $this->ShowAllReviews();
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
    
    public function EditReview($id)
    {
        $user = $this->GetUserInformation();
        $safe = $this->ScrubIndexNumber($id);

        require_once "model/Books.Model.php";
        $bookDB = new BooksModel();

        $formData['Review'] = $this->db->GetReview($safe);
        if ($formData['Review'])
        {
            $formData['Book'] = $bookDB->GetBook($formData['Review']['BookId']);
            if ($formData['Book'])
            {
                if (str_contains($user['Roles'], "Admin") || $user['Id'] == $formData['Review']['UserId'])
                {
                    require_once "views/default.php";
                    require_once "views/reviews.php";
                    echo StartPage("Editera recension");
                    IndexNav($user['Roles'],$user['Username']);
                    echo EditReview($formData,$user);
                    echo EndPage();
                }
                else
                {
                    $this->ShowError("Du har inte behörighet att göra detta");
                }
            }
            else
            {
                $this->ShowError("Finns ingen bok kopplad till denna recensionen");
            }
        }
        else
        {
            $this->ShowError("Recensionen finns inte");
        }

    }

    public function DeleteReview($id)
    {
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $result = $this->db->HideReview($safe);
            if ($result)
            {
                $this->ShowAllReviews();
            }
            else
            {
                $this->ShowError("Recensionen finns inte");
            }
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta!!");
        }
    }

    public function UnDeleteReview($id)
    {
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $result = $this->db->ReviveReview($safe);
            if ($result)
            {
                require_once "controller/Admin.Controller.php";
                $controllerAdmin = new AdminController();
                $controllerAdmin->AdminPanel();
            }
            else
            {
                $this->ShowError("Recensionen finns inte");
            }
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta!!");
        }
    }

    public function UpdateReview($id)
    {
        $user = $this->GetUserInformation();
        $safe = $this->ScrubIndexNumber($id);
        $review = $this->db->GetReview($safe);
        //lägga till att den som skrev review ska kunna uppdatera den också

        if (str_contains($user['Roles'], "Admin") || $user['Id'] == $review['UserId'])
        {
            //Förbereder arrayen som skall in i databasen
            $arr = array (
                $_POST['Title'],$_POST['Text'],$_POST['Rating'],$safe
            );
            $arr = $this->ScrubSaveAuthorArr($arr);
            if (!$this->CheckIfNullOrEmptyArr($arr))
            {
                $this->db->UpdateReview($arr);
                //ShowReview läser av $_POST['id'] för att veta vilken review som skall visas
                $_POST['id'] = $safe;
                $this->ShowReview($safe);
            }
            else
            {
                $this->ShowError("Valideringen av datamisslyckades");
            }
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta!!");
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
      $safe = htmlspecialchars(trim(str_replace($banlist,"",$notsafeText)));
      return $safe;
    }

    private function CheckIfNullOrEmptyArr($arr)
    {
        for ($i=0; $i < count($arr); $i++) { 
            if (is_null($arr[$i]) || $arr[$i] == "" )
            {
                return true;
            }
        }
        return false;
    }
}
?>