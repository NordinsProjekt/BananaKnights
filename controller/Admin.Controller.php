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
        //TODO VALIDERA
        //Inloggad och vara admin
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
            IndexNav("Admin",$user['Username']);
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
}
?>