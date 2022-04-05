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
        $page = "";
        $page .= StartPage("Skapa ny Bok");
        $page .= CreateNewBook();
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