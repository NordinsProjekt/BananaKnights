<?php
require_once "model/Books.Model.php";
class BooksController
{
    private $db;

    function __construct()
    {
        $this->db = new BooksModel();
    }

    function __destruct()
    {
        
    }

    function CreateBook()
    {
        //TODO Kontrollera behörighet
        if ($this->VerifyUserRole("Admin"))
        {
            require_once "views/books.php";
            require_once "views/default.php";
            require_once "model/Authors.Model.php";
            $arrGenre = $this->db->GetAllGenres();
            $authorTable = new AuthorsModel();
            $arrAuthor = $authorTable->GetAllAuthors();
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= CreateNewBook($arrGenre,$arrAuthor);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Kan inte visa sidan");
        }

    }

    function ShowBook()
    {
        $role = "";
        if ($this->VerifyUserRole("User"))
        {
            $role = "User";
        }
        $safetext = $this->ScrubInputs($_POST['id']);
        $result = $this->db->GetBook($safetext);
        if ($result)
        {
            if (file_exists("img/books/". $result['ImagePath']))
            {
                $pictures = scandir("img/books/". $result['ImagePath']);
                $imageLink = prefix."img/books/". $result['ImagePath'] ."/". $pictures[2];
            }
            else
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= ShowBook($result,$imageLink,$role);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Boken finns inte");
        }
    }      

    function DeleteBook()
    {
        if ($this->VerifyUserRole("Admin"))
        {
            $safetext = $this->ScrubInputs($_POST['id']);
            if($this->db->HideBook($safetext))
            {
                echo "Boken är nu borta";
            }
            else
            {
                $this->ShowError("Boken kunde inte tas bort");
            }
        }
        else
        {
            $this->ShowError("Inga rättigheter för detta");
        }

    }

    function ShowSearchBook($searchinput)
    {
        $role = "User";
        if ($this->VerifyUserRole("Admin"))
        {
            $role = "Admin";
        }
        $safetext = $this->ScrubInputs($searchinput);
        $result = $this->db->GetAllBooksSearch($safetext);
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Sök Resultat");
            $page .= NavigationPage();
            $page .= ShowAllBooks($result,$role);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= NavigationPage();
            $page .= "<h1>Sök Resultat</h1><p>'".$_POST['search']."' gav inga resultat!</p>";
            $page .= EndPage();
            echo $page;
        }
    }

    function ShowAllBooks()
    {
        $role = "User";
        if ($this->VerifyUserRole("Admin"))
        {
            $role = "Admin";
        }
        $result = $this->db->GetAllBooks();
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= ShowAllBooks($result,$role);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= NavigationPage();
            $page .= "<h1>Visa alla böcker</h1><p>Finns inga böcker att visa</p>";
            $page .= EndPage();
            echo $page;
        }
    }
    function CreateGenre()
    {
        //TODO Kontrollera behörighet
        if ($this->VerifyUserRole("Admin"))
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Genre");
            $page .= NavigationPage();
            $page .= CreateNewGenre();
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Kan inte visa sidan");
        }

    }

    function SaveGenre()
    {
        //Slarvig funktion men funkar.
        if ($this->VerifyUserRole("Admin"))
        {
            $arr = array(
                $this->ScrubInputs($_POST['BookGenre']),
                $this->ScrubInputs($_POST['GenreDescription']),
                date("Y-m-d H:i:s")
            );
            if ($this->ValidateSaveGenre($arr))
            {
                $this->db->SetGenre($arr);
                echo "Genre lades till";
            }
            else
            {
                $this->ShowError("Genre kunde inte skapas, valideringsfel av data");
            }
        }
        else
        {
            $this->ShowError("Inga rättigheter att göra detta");
        }

    }

    function ShowGenre()
    {

    }

    function EditGenre()
    {

    }

    function UpdateGenre()
    {

    }

    function ShowAllGenre()
    {
        $role = "User";
        if ($this->VerifyUserRole("Admin"))
        {
            $role = "Admin";
        }
        $result = $this->db->GetAllGenres();
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= ShowAllGenre($result,$role);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= NavigationPage();
            $page .= "<h1>Visa alla böcker</h1><p>Finns inga böcker att visa</p>";
            $page .= EndPage();
            echo $page;
        }
    }
    public function ShowError($errorText)
    {
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Fel vid inläsning");
        $page .= NavigationPage();
        $page .= "<h1>FEL</h1><p>" . $errorText . "</p>";
        $page .= EndPage();
        echo $page;
    }

    public function SaveBook()
    {
        if ($this->VerifyUserRole("Admin"))
        {

        }
        else
        {
            $this->ShowError("Du har inga rättigheter för detta");
            exit();
        }
        require_once "classes/Book.class.php";
        $book = new Book($_SESSION['UserId'],$_POST['BookTitle'],$_POST['BookYear'],
        $_POST['BookDescription'],$_POST['BookISBN'],$_POST['BookISBN'].$_POST['BookTitle'],"0",date("Y-m-d H:i:s"));

        //TODO:
        //Om $_POST saknar info så visa felmeddelande direkt AJAX
        //Om arrayen inte innehåller något som är tomt eller felaktig data
        if($book->Validated())
        {
            //result = lastID
            $result = $this->db->SetBook($book->ToArray());
            if ($this->AddAuthorToBook($result['Id'],$_POST['BookAuthor']))
            {
                if ($this->AddGenreToBook($result['Id'],$_POST['BookGenre']))
                {
                    require_once "controller/Upload.Controller.php";
                    $uploadController = new UploadController();
                    if ($uploadController->AddImage("img/books/".$book->getImagePath(),$_FILES['BookPicture']))
                    {
                        echo "Allt gick bra";
                    }
                    else
                    {
                        echo "Något var fel med bilden";
                    }
                }
                else
                {
                    $this->ShowError("Fel i Skapa Bok formuläret Lägga till genre");
                    //Annars radera boken
                }
            }
            else
            {
                $this->ShowError("Fel i Skapa Bok formuläret lägga till author");
                //Annars radera boken
            }
        }
        else
        {
            $this->ShowError("Fel i Skapa Bok formuläret validering av data");
        }
    }

    public function EditBook()
    {
        if ($this->VerifyUserRole("Admin"))
        {
            echo "Visa Edit formulär";
        }
        else
        {
            $this->ShowError("Du har inga rättigheter för detta");
        }
    }

    private function ValidateSaveGenre($arr)
    {
        //Kontrollerar Genre arrayen innan databasen
        if (empty($arr[0]) || $arr[0] == "") {return false;}
        if (empty($arr[0]) || $arr[0] == "") {return false;}
        return true;
    }

    public function DeleteGenre()
    {
        if ($this->VerifyUserRole("Admin"))
        {
            $cleanId = $this->ScrubInputs($_POST['id']);
            $result = $this->db->DeleteGenre($cleanId);
            if ($result)
            {
            
            }
        }
        else
        {
            $this->ShowError("Kräver högre rättighet för detta");
        }
    }

    private function AddGenreToBook($bookId,$genreId)
    {
        $arr = array($genreId,$bookId);
        $cleanArr = $this->ScrubSaveBookArr($arr);
        if (!$this->CheckIfNullOrEmptyArr($cleanArr))
        {
            $this->db->AddGenreToBook($cleanArr);
            return true;
        }
        return false;
    }

    private function AddAuthorToBook($bookId,$authorId)
    {
        $arr = array($bookId,$authorId);
        $cleanArr = $this->ScrubSaveBookArr($arr);
        if (!$this->CheckIfNullOrEmptyArr($cleanArr))
        {
            $this->db->AddAuthorToBook($cleanArr);
            return true;
        }
        return false;
    }

    private function ScrubSaveBookArr($arr)
    {
        $cleanArr = array();
        for ($i=0; $i < count($arr); $i++) { 
            $cleanArr[] = $this->ScrubInputs($arr[$i]);
        }
        return $cleanArr;
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

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
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