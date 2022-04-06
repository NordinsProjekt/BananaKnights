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

    function Create()
    {
        require_once "views/books.php";
        require_once "views/default.php";
        $arrGenre = array (
            "Skräck", "Fantasy"
        );
        $arrAuthor = array (
            "H.P Lovecraft", "JK Rowling"
        );
        $page = "";
        $page .= StartPage("Skapa ny Bok");
        $page .= CreateNewBook($arrGenre,$arrAuthor);
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
            $page .= ShowBook($result);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Boken finns inte");
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
            $page .= ShowAllBooks($arr);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= "<h1>FEL</h1><p>Kunde inte hämta Alla Böcker</p>";
            $page .= EndPage();
        }
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

    public function SaveBook($session)
    {
        //TODO:
        //Har inte med validering för varje specifik fält
        //Om $_POST saknar info så visa felmeddelande direkt
        $arr = array (
            $session['UserID'],$_POST['BookTitle'],$_POST['BookDescription'],
            $_POST['BookISBN'],"img/".$_POST['BookISBN'],"0",date("Y-m-d H:i:s")
        );
        $cleanArr = $this->ScrubSaveBookArr($arr);
        //Om arrayen inte innehåller något som är tomt
        if(!$this->CheckIfNullOrEmptyArr($cleanArr))
        {
            //result = lastID
            $result = $this->db->SetBook($arr);
            if (is_numeric($result))
            {
                //sen sätt in genre och författare i sina tabeller
            }
            
            
        }
        else
        {
            $this->ShowError("Fel i Skapa Bok formuläret validering av data");
        }
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