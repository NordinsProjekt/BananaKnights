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
        $safe = $this->ScrubIndexNumber($id);
        $user = $this->GetUserInformation();
        $result = $this->db->GetBook($id);

        if ($result)
        {
            if ($user['Roles'] != "")
            {
                $usefull = $this->db->IsRecommendedSet($safe,$user['Id']);
                if ($usefull['Antal'] == 1)
                {
                    $result+= ["Recommend" => true];
                }
                else
                {
                    $result+= ["Recommend" => false];
                }
            }
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
            require_once "views/quiz.php";
            echo StartPage("Visa bok");
            IndexNav($user['Roles'],$user['Username']);
            echo nl2br(ShowBook($result,$imageLink,$user['Roles']));
            require_once "model/Reviews.Model.php";
            require_once "model/Quiz.Model.php";
            $reviewDB = new ReviewsModel();
            $reviews = $reviewDB->GetAllReviewsBook($result['Id']);
            $quizDB = new QuizModel();
            $quiz = $quizDB->GetAllQuizForBook($id);
            if ($reviews)
            {
                echo ShowAllReviews($reviews,$user['Roles']);
            }
            if ($quiz)
            {
                echo ShowAllQuiz($quiz,$user['Roles']);
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
                require_once "controller/Admin.Controller.php";
                $controllerAdmin = new AdminController();
                $controllerAdmin->AdminPanel();
            }
            else
            {
                $this->ShowError("Boken kunde inte tas bort");
            }
        }
        else
        {
            $this->ShowError("Inga r??ttigheter f??r detta");
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
            $this->ShowError("Inga r??ttigheter f??r detta");
        }

    }

    function ShowSearchBook($searchinput)
    {
        $user = $this->GetUserInformation();
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

            echo StartPage("S??k Resultat");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAllBooks($result,$user);
            echo EndPage();
        }
        else
        {
            require_once "views/default.php";
            echo StartPage("Fel vid inl??sning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1 class='display-4' style='text-align: center; padding-top: 50px;'>S??k Resultat</h1><p style='text-align: center;'>Hittade ingenting</p>";
            echo EndPage();
        }
    }

    function ShowAllBooks()
    {
        $user = $this->GetUserInformation();
        $result = $this->db->GetAllBooks();
        if ($result)
        {
            require_once "views/books.php";
            require_once "views/default.php";
            echo StartPage("Skapa ny Bok");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAllBooks($result,$user);
            echo EndPage();
        }
        else
        {
            require_once "views/default.php";
            echo StartPage("Fel vid inl??sning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1 class='display-4' style='text-align: center; padding-top: 50px;'>Visa alla b??cker</h1><p style='text-align: center;'>Finns inga b??cker att visa</p>";
            echo EndPage();
        }
    }
    function CreateGenre()
    {
        //TODO Kontrollera beh??righet
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
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $arr = array(
                $this->ScrubInputs($_POST['BookGenre']),
                $this->ScrubInputs($_POST['GenreDescription']),
                date("Y-m-d H:i:s")
            );
            if ($this->ValidateSaveGenre($arr))
            {
                $this->db->SetGenre($arr);
                header("Location:".prefix."books/showallgenre");
            }
            else
            {
                $this->ShowError("Genre kunde inte skapas, valideringsfel av data");
            }
        }
        else
        {
            $this->ShowError("Inga r??ttigheter att g??ra detta");
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
                $this->ShowError("N??got gick fel med valideringen av data");
            }

        }
        else
        {
            $this->ShowError("Du har inte r??ttigheter f??r detta");
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
            $this->ShowError("Du har inte r??ttigheter f??r detta");
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
            echo StartPage("Fel vid inl??sning");
            IndexNav($user['Roles'],$user['Username']);
            echo "<h1 class='display-4' style='text-align: center; padding-top: 50px;'>Visa alla genre</h1><p style='text-align: center;'>Finns inga genre att visa</p>";
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
            //Om $_POST saknar info s?? visa felmeddelande direkt AJAX
            //Om arrayen inte inneh??ller n??got som ??r tomt eller felaktig data
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
                            echo "N??got var fel med bilden";
                        }
                        header("Location: ".prefix."books/showall");
                    }
                    else
                    {
                        $this->ShowError("Fel i Skapa Bok formul??ret L??gga till genre");
                        //Annars radera boken
                    }
                }
                else
                {
                    $this->ShowError("Fel i Skapa Bok formul??ret l??gga till author");
                    //Annars radera boken
                }
            }
            else
            {
                $this->ShowError("Fel i Skapa Bok formul??ret validering av data");
            }
        }
        else
        {
            $this->ShowError("Du har inga r??ttigheter f??r detta");
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
            $this->ShowError("Du har inga r??ttigheter f??r detta");
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
                //Uppdaterar f??rfattare och genre samtidigt.
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
            $this->ShowError("Du har inga r??ttigheter f??r detta");
        }
           
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
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $cleanId = $this->ScrubInputs($_POST['id']);
            $result = $this->db->DeleteGenre($cleanId);
            if ($result)
            {
                $this->ShowAllGenre();
            }
            else
            {
                $this->ShowError("Kunde inte radera genre");
            }
        }
        else
        {
            $this->ShowError("Kr??ver h??gre r??ttighet f??r detta");
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
            $this->ShowError("Du har inte r??ttighet till detta");
        }
    }

    //H??mtar tillbaka en genre fr??n Hide/Delete
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
            $this->ShowError("Du har inte r??ttighet f??r detta");
        }
    }

    public function ShowTop5Books()
    {
        $user = $this->GetUserInformation();
        $books = $this->db->GetBookAVGRatingTop5();
        if ($books)
        {
            require_once "views/default.php";
            echo StartPage("5 b??cker som ??r rankade h??gst");
            IndexNav($user['Roles'],$user['Username']);
            echo IndexCardsV2($books);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Finns inte 5 b??cker med reviews");
        }

    }
    
    public function ShowLow5Books()
    {
        $user = $this->GetUserInformation();
        $books = $this->db->GetBookAVGRatingLowest5();
        if ($books)
        {
            require_once "views/default.php";
            echo StartPage("5 b??cker som ??r rankade sist");
            IndexNav($user['Roles'],$user['Username']);
            echo IndexCardsV2($books);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Finns inte 5 b??cker med reviews");
        }
    }

    public function RecommendBook($id)
    {
        $user = $this->GetUserInformation();
        $safe = $this->ScrubIndexNumber($id);
        if (str_contains($user['Roles'],"User") || str_contains($user['Roles'],"Moderator") || str_contains($user['Roles'],"Admin") && $safe > 0)
        {
            //Kollar om det anv??ndaren har tryckt p?? knappen eller inte
            $result = $this->db->IsRecommendedSet($safe,$user['Id']);
            if ($result['Antal'] == 1)
            {
                //Radera i databasen
                $this->db->DeleteRecommendBook($safe,$user['Id']);
                $this->ShowBook($safe);
            }
            else
            {
                //L??gg till i databasen
                $this->db->SetRecommendBook($safe,$user['Id']);
                $this->ShowBook($safe);
            }
        }
        else
        {
            $this->ShowError("Logga in om du vill anv??nda denna funktionen");
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

    //Enkel scrubmetod som rensar bort d??liga tecken
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