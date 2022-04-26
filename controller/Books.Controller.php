<?php
require_once "model/Books.Model.php";
require_once "classes/Base.Controller.class.php";
class BooksController extends BaseController
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
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin") || str_contains($user['Roles'],"User"))
        {
            require_once "views/books.php";
            require_once "views/default.php";
            require_once "model/Authors.Model.php";
            $arrGenre = $this->db->GetAllGenres();
            $authorTable = new AuthorsModel();
            $arrAuthor = $authorTable->GetAllAuthors();
            echo StartPage("Skapa ny Bok");
            IndexNav($user['Roles'],$user['Username']);            
            echo CreateNewBook($arrGenre,$arrAuthor);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Kan inte visa sidan");
        }

    }

    function ShowBook($id)
    {
        $user = $this->GetUserInformation();
        $result = $this->db->GetBook($id);

        if ($result)
        {
            require_once "model/Reviews.Model.php";
            $reviewDB = new ReviewsModel();
            $reviews = $reviewDB->GetAllReviewsBook($id);
            $totalRating = 0;
            if (!empty($reviews))
            {
                foreach ($reviews as $key => $row) {
                    $totalRating += $row['Rating'];
                }
                (int)$totalRating = $totalRating/count($reviews);
            }
            if ($totalRating == 0)
            { 
                $result['Rating'] = "n/a";
            }
            else 
            {
                $result['Rating'] = $totalRating;
            }

            if (file_exists("img/books/". $result['ImagePath']))
            {
                $pictures = scandir("img/books/". $result['ImagePath']);
                if (!empty($pictures[2]))
                {
                    $imageLink = prefix."img/books/". $result['ImagePath'] ."/". $pictures[2];
                }
                else
                {
                    $imageLink = prefix."img/books/noimage.jpg";
                }
                
            }
            else
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            require_once "views/books.php";
            require_once "views/default.php";
            require_once "views/reviews.php";
            echo StartPage("Visa bok");
            IndexNav($user['Roles'],$user['Username']);
            echo nl2br(ShowBook($result,$imageLink,$user['Roles']));
            require_once "model/Reviews.Model.php";
            $reviewDB = new ReviewsModel();
            $reviews = $reviewDB->GetAllReviewsBook($result['Id']);
            if ($reviews)
            {
                echo ShowAllReviews($reviews,$user['Roles']);
            }
            echo EndPage();
          }
        else
        {
            $this->ShowError("Boken finns inte");
        }
    }      

    public function UnDeleteBook()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            if($this->db->ReviveBook($safe))
            {
                $this->ShowAllBooks();
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
    public function DeleteBook()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safetext = $this->ScrubIndexNumber($_POST['id']);
            if($this->db->HideBook($safetext))
            {
                $this->ShowAllBooks();
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
        $role = "";
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $role = "Admin";
        }
        $safetext = $this->ScrubInputs($searchinput);


        if(isset($_POST['search']))
        {
            $result = $this->db->GetAllBooksSearch($safetext);
        }
        if(isset($_POST['author']))
        {
            $result = $this->db->GetAllBooksAuthorSearch($safetext);
        }
        if(isset($_POST['genre']))
        {
            $result = $this->db->GetAllBooksGenreSearch($safetext);
        }

        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";

            echo StartPage("Sök Resultat");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAllBooks($result,$role);
            echo EndPage();
        }
        else
        {
            require_once "views/default.php";
            echo StartPage("Fel vid inläsning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1>Sök Resultat</h1><p>'".$_POST['search']."' gav inga resultat!</p>";
            echo EndPage();
        }
    }

    function ShowAllBooks()
    {
        $role = "User";
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $role = "Admin";
        }
        $result = $this->db->GetAllBooks();
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            echo StartPage("Skapa ny Bok");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAllBooks($result,$role);
            echo EndPage();
        }
        else
        {
            require_once "views/default.php";
            echo StartPage("Fel vid inläsning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1>Visa alla böcker</h1><p>Finns inga böcker att visa</p>";
            echo EndPage();
        }
    }
    function CreateGenre()
    {
        //TODO Kontrollera behörighet
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            require_once "views/books.php";
            require_once "views/default.php";
            echo StartPage("Skapa ny Genre");
            IndexNav($user['Roles'],$user['Username']);
            echo CreateNewGenre();
            echo EndPage();
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
                header("Location:".prefix."books/showallgenre");
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

    function ShowGenre($id)
    {
        $user = $this->GetUserInformation();
        $safe = $this->ScrubIndexNumber($id);
        $result = $this->db->GetGenre($safe);
        if ($result)
        {
            $dataArr['Genre'] = $result;
            $dataArr['Books'] = $this->db->GetAllBooksSortedByTitle($safe);
            require_once "views/default.php";
            require_once "views/books.php";
    
            echo StartPage("Visa Genre");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowGenre($dataArr);
            echo EndPage();
        }

        

    }

    public function UpdateGenre($genreId)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $genreId = $this->ScrubIndexNumber($genreId);
            $genreArr = array (
                $genreId, $_POST['BookGenre'],$_POST['GenreDescription']
            );
            unset ($_SESSION['form']);
            if ($this->ValidateSaveGenre($genreArr))
            {
                $this->db->UpdateGenre($genreArr[0],$genreArr[1],$genreArr[2]);
                $this->ShowAllGenre();
            }
            else
            {
                $this->ShowError("Något gick fel med valideringen av data");
            }

        }
        else
        {
            $this->ShowError("Du har inte rättigheter för detta");
        }
    }

    public function EditGenre($genreId)
    {
        $safe = $this->ScrubIndexNumber($genreId);
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $genre = $this->db->GetGenre($safe);
            require_once "views/default.php";
            require_once "views/books.php";
            echo StartPage("Editera genre");
            IndexNav($user['Roles'],$user['Username']);
            echo EditGenre($genre,$user['Roles']);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du har inte rättigheter för detta");
        }
    }

    function ShowAllGenre()
    {
        $user = $this->GetUserInformation();
        $result = $this->db->GetAllGenres();
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            echo StartPage("Visa alla genre");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAllGenre($result,$user['Roles']);
            echo EndPage();
        }
        else
        {
            require_once "views/default.php";
            echo StartPage("Fel vid inläsning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1>Visa alla genre</h1><p>Finns inga genre att visa</p>";
            echo EndPage();
        }
    }

    public function SaveBook()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Admin"))
        {
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
                        header("Location: ".prefix."books/showall");
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
        else
        {
            $this->ShowError("Du har inga rättigheter för detta");
            exit();
        }
        

    }

    public function EditBook($id)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'], "Admin"))
        {
            $safe = $this->ScrubIndexNumber($id);
            $book = $this->db->GetBook($safe);
            require_once "model/Authors.Model.php";
            $authorDB = new AuthorsModel();
            $formData['Book'] = $book;
            $formData['Genres'] = $this->db->GetAllGenres();
            $formData['Authors'] = $authorDB->GetAllAuthors();
            require_once "views/default.php";
            require_once "views/books.php";
            echo StartPage("Editera bok");
            IndexNav($user['Roles'],$user['Username']);
            echo EditBook($formData,$user['Roles']);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du har inga rättigheter för detta");
        }
    }

    public function UpdateBook($id)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'], "Admin"))
        {
            $safe = $this->ScrubIndexNumber($id);
            //Saknar kontroll av datan
            $arr = array (
                $_POST['BookTitle'],$_POST['BookYear'],$_POST['BookDescription'],
                $_POST['BookISBN'],$_POST['BookPicturePath'],$safe
            );
            if (!$this->CheckIfNullOrEmptyArr($this->ScrubSaveBookArr($arr)))
            {
                $this->db->UpdateBook($arr);
                //Uppdaterar författare och genre samtidigt.
                $this->db->UpdateAuthorBook($_POST['BookAuthor'],$safe);
                $this->db->UpdateGenreBooks($safe,$_POST['BookGenre']);
                $this->ShowAllBooks();
            }
            else
            {
                $this->ShowError("Valideringen misslyckades");
            }

        }
        else
        {
            $this->ShowError("Du har inga rättigheter för detta");
        }
           
    }
    private function ValidateUpdateBook($arr)
    {
        if ($arr[0] == NULL || $arr[0] = "")
        {
            return false;
        }
        return true;
    }

    private function ValidateSaveGenre($arr)
    {
        //Kontrollerar Genre arrayen innan databasen
        foreach ($arr as $key => $value) {
            if (empty($value) || $value == "") {return false;}
        }
        return true;
    }

    public function DeleteGenre()
    {
        if ($this->VerifyUserRole("Admin"))
        {
            $cleanId = $this->ScrubInputs($_POST['id']);
            $result = $this->db->DeleteGenre
            ($cleanId);
            if ($result)
            {
                $this->ShowAllGenre();
            }
        }
        else
        {
            $this->ShowError("Kräver högre rättighet för detta");
        }
    }
    
    public function HideGenre()
    {
        
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            $this->db->HideGenre($safe);
            $this->ShowAllGenre();
        }
        else
        {
            $this->ShowError("Du har inte rättighet till detta");
        }
    }

    //Hämtar tillbaka en genre från Hide/Delete
    public function ReviveGenre()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            $this->db->ReviveGenre($safe);
            $this->ShowAllGenre();
        }
        else
        {
            $this->ShowError("Du har inte rättighet för detta");
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

    //Enkel scrubmetod som rensar bort dåliga tecken
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

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }
}
?>