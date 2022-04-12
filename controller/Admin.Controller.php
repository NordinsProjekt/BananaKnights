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
        
        //Visa upp adminpanelen.
        require_once "views/default.php";
        require_once "views/books.php";
        require_once "views/authors.php";
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
        $page .= "<div class'book'>". CreateNewBook($arrGenre,$arrAuthor) . "</div>";
        $page .= "</div>";
        $page.= EndPage();
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