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
    function CreateGenre()
    {
        //TODO Kontrollera behörighet
        require_once "views/books.php";
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Skapa ny Genre");
        $page .= NavigationPage();
        $page .= CreateNewGenre();
        $page .= EndPage();
        echo $page;
    }

    function ShowBook($id)
    {
        $safetext = $this->CheckUserInputs($id);
        $result = $this->db->GetBook($id);
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= ShowBook($result);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Boken finns inte");
        }
    }
    function DeleteBook($bookId)
    {
        //TODO kontrollera behörighet
        if($this->db->HideBook($bookId))
        {
            echo "Boken är nu borta";
        }
        else
        {
            $this->ShowError("Boken kunde inte tas bort");
        }
    }
    function ShowAllBooks()
    {
        if ($arr = $this->db->GetAllBooks())
        {
            require_once "views/books.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Bok");
            $page .= NavigationPage();
            $page .= ShowAllBooks($arr);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= NavigationPage();
            $page .= "<h1>FEL</h1><p>Kunde inte hämta Alla Böcker</p>";
            $page .= EndPage();
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

    public function SaveBook($session)
    {
        //TODO:
        //Har inte med validering för varje specifik fält
        //Om $_POST saknar info så visa felmeddelande direkt
        $arr = array (
            $session['UserID'],$_POST['BookTitle'],$_POST['BookYear'],$_POST['BookDescription'],
            $_POST['BookISBN'],"img/".$_POST['BookISBN'],"0",date("Y-m-d H:i:s")
        );
        
        $cleanArr = $this->ScrubSaveBookArr($arr);
        //Om arrayen inte innehåller något som är tomt
        if(!$this->CheckIfNullOrEmptyArr($cleanArr))
        {
            //result = lastID
            $result = $this->db->SetBook($arr);
            if ($this->AddAuthorToBook($result['Id'],$_POST['BookAuthor']))
            {
                if ($this->AddGenreToBook($result['Id'],$_POST['BookGenre']))
                {
                    echo "Boken lades till";
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
    function SaveGenre($session)
    {
        //Behöver validering endast admin ska kunna detta
        //(Name,Description,Created)
        $arr = array(
            $_POST['BookGenre'],$_POST['GenreDescription'],date("Y-m-d H:i:s")
        );
        $cleanArr = $this->ScrubSaveBookArr($arr);
        if (!$this->CheckIfNullOrEmptyArr($cleanArr))
        {
            $this->db->SetGenre($arr);
            echo "Genre lades till";
        }
        else
        {
            $this->ShowError("Genre kunde inte skapas, valideringsfel av data");
        }
    }
    public function DeleteGenre($id)
    {
        $cleanId = $this->CheckUserInputs($id);
        $this->db->DeleteGenre($id);
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
            $cleanArr[] = $this->CheckUserInputs($arr[$i]);
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

    private function CheckUserInputs($notsafeText)
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