<?php
require_once "model/Admin.Model.php";
require_once "classes/Base.Controller.class.php";
class AdminController extends BaseController
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
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
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
            echo StartPage("Adminpanel");
            IndexNav($user['Roles'],$user['Username']);
            echo "<div class='AdminBook'><div class='genre'>" . CreateNewGenre() . "</div>";
            echo "<div class='author'>". AddNewAuthor() . "</div>";
            echo "<div class='book'>". CreateNewBook($arrGenre,$arrAuthor) . "</div>";
            echo "</div>";
            echo EndPage();
        }
        else
        {
            $this->ShowError("Inga rättigheter för detta");
        }
    }

    public function AdminPanel()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            require_once "views/default.php";
            require_once "views/admin.php";
            require_once "model/User.Model.php";
            require_once "model/Authors.Model.php";
            require_once "model/Reviews.Model.php";
            require_once "model/Books.Model.php";

            $userDB = new UserModel();
            $authorDB = new AuthorsModel();
            $reviewDB = new ReviewsModel();
            $bookDB = new BooksModel();

            echo StartPage("Adminpanel");
            IndexNav($user['Roles'],$user['Username']);
            //Adminpanelen kommer behöva många listor av olika saker.
            //Bygg en formData array för allt.
            $formData['BannedUsers'] = $userDB->GetAllLockedAccounts();
            $formData['BannedAuthors'] = $authorDB->GetAllFlaggedAuthors();
            $formData['BannadeReviews'] = $reviewDB->GetAllFlaggedReviews();
            $formData['BannedGenre'] = $bookDB->GetAllDeletedGenre();
            $formData['DeletedAuthors'] = $authorDB->GetAllDeletedAuthors();
            echo AdminIndex($formData);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Saknas rättighet för att komma in här");
        }
    }

    public function ShowUser()
    {
        $user = $this->GetUserInformation();
        if (isset($_POST['id']) && str_contains($user['Roles'],"Admin"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            if (is_numeric($safe) && $safe>0)
            {
                require_once "model/User.Model.php";
                $userDB = new UserModel();
                $rolesArr = $userDB->GetAllRoles();
                $userRolesArr = $userDB->GetAllRolesFromUser($safe);
                $userResult = $userDB->GetEntireUser($safe);
                if ($userResult)
                {
                    $arr  = array(
                        "AllRoles"=>$rolesArr,
                        "UserRoles"=>$userRolesArr,
                        "User"=>$userResult
                    );
                    require_once "views/default.php";
                    require_once "views/admin.php";
                    echo StartPage("Visa användare");
                    IndexNav($user['Roles'],$user['Username']);
                    echo ShowUserAdmin($arr);
                    echo EndPage();
                }
                else
                {
                    $this->ShowError("Kan inte visa formuläret!!");
                }
            }
        }
        else
        {
            $this->ShowError("Du har inte rättigheter för detta");
        }
    }
    public function ShowAllUsers()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            require_once "model/User.Model.php";
            $userDB = new UserModel();

            $result = $userDB->GetAll();
            if ($result)
            {
                require_once "views/admin.php";
                require_once "views/default.php";
                echo StartPage("Adminpanel");
                IndexNav($user['Roles'],$user['Username']);
                echo ShowAllUsersAdmin($result,"Admin");
                echo EndPage();
            }
        }
        else
        {
            $this->ShowError("Du har inte rättigheter för detta");
        }
    }
    public function CreateUserRole()
    {

    }

    public function SaveUserRole()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {

        }
    }

    public function AddUserRole()
    {
        $user = $this->GetUserInformation();
        $name = $this->ScrubFormName($_POST['formname']);
        if (str_contains($user['Roles'],"Admin"))
        {
            $formArr = $_SESSION['form'][$name];
            unset($_SESSION['form']);
            require_once "model/User.Model.php";
            $userDB = new UserModel();
            $result = $userDB->SetUserGroup($this->ScrubIndexNumber($formArr['roleId']),
            $this->ScrubIndexNumber($formArr['userId']));
            if ($result)
            {
                //Sätter in ID:t till användaren som skall visas
                $_POST['id'] = $this->ScrubIndexNumber($formArr['userId']);
                //Läser in samma användare som vi har jobbat med
                $this->ShowUser();
            }
            else
            {
                $this->ShowError("Kunde inte ta bort behörigheten");
            }
        }
    }

    public function RemoveUserRoleFromUser()
    {
        $user = $this->GetUserInformation();
        $name = $this->ScrubFormName($_POST['formname']);
        if (str_contains($user['Roles'],"Admin"))
        {
            $formArr = $_SESSION['form'][$name];
            unset($_SESSION['form']);
            require_once "model/User.Model.php";
            $userDB = new UserModel();
            $result = $userDB->RemoveRoleFromUser($this->ScrubIndexNumber($formArr['roleId']),
            $this->ScrubIndexNumber($formArr['userId']));
            if ($result)
            {
                //Sätter in ID:t till användaren som skall visas
                $_POST['id'] = $this->ScrubIndexNumber($formArr['userId']);
                //Läser in samma användare som vi har jobbat med
                $this->ShowUser();
            }
            else
            {
                $this->ShowError("Kunde inte ta bort behörigheten");
            }
        }
    }
}
?>