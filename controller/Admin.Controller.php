<?php
require_once "model/Admin.Model.php";
class AdminController
{
    private $db;

    function __construct()
    {
        $this->db = new AdminModel();
    }

    function __destruct()
    {
        
    }

    public function Show()
    {
        //TODO VALIDERA
        //Inloggad och vara admin
        if ($this->VerifyUserRole("Admin"))
        {
            //Visa upp adminpanelen.
            require_once "views/default.php";
            require_once "views/books.php";
            require_once "views/authors.php";
            require_once "views/reviews.php";
            require_once "model/Books.Model.php";
            require_once "model/Authors.Model.php";
            $booksTable = new BooksModel();
            $authorTable = new AuthorsModel();
            $arrGenre = $booksTable->GetAllGenres();
            $arrAuthor =  $authorTable->GetAllAuthors();
            $page = "";
            $page .= StartPage("Adminpanel");
            $page .= NavigationPage();
            $page .= "<div class='AdminBook'><div class='genre'>" . CreateNewGenre() . "</div>";
            $page .= "<div class='author'>". AddNewAuthor() . "</div>";
            $page .= "<div class='book'>". CreateNewBook($arrGenre,$arrAuthor) . "</div>";
            $page .= "</div>";
            $page.= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Inga rättigheter för detta");
        }

    }

    private function ShowError($errorText) //Sida som visar fel
    {
        $role = "";
        require_once "views/default.php";
        echo StartPage("Fel vid inläsning");
        if ($this->VerifyUserRole("User"))
        {
            $role = "User";
            require_once "model/User.Model.php";
            $userDB = new UserModel();
            $user = $userDB->GetUserFromId($_SESSION['UserId']);
            if ($this->VerifyUserRole("Admin"))
            {
                $role = "Admin";
            }
            IndexNav($role,$user['UserName']);
            echo "<h1>FEL</h1><p>" . $errorText . "</p>";
            echo EndPage();
        }
        else
        {
            IndexNav("","");
            echo "<h1>FEL</h1><p>" . $errorText . "</p>";
            echo EndPage();
        }
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