<?php
session_start();
const prefix = "/BananaKnights/";

function ScrubUserInputs($notsafeText)
{
  $banlist = array("\t","%",";","/","<",">",")","(","=","[","]","+","*","#");
  $safe = trim(str_replace($banlist,"",$notsafeText));
  $safe = stripslashes(htmlspecialchars($safe));
  return $safe;
}
?>

<?php
//Delar upp url:en för funktioner inom api:et
//Kontrollerar så token är giltig

//Testar säkerhet för att göra formdata mer skyddad mot attacker
if (isset($_POST['formname']) && key_exists('form',$_SESSION))
{
    $name = ScrubUserInputs($_POST['formname']);
    if (isset($_SESSION['form'][$name]))
    {
        $arr = explode("/",$_SESSION['form'][$name]['FormAction']);
        switch($arr[2])
        {
            case "admin":
                AdminRoute($arr[3]);
                break;
            case "author":
                AuthorRoute($arr[3]);
                break;
            case "review":
                ReviewRoute($arr[3]);
                break;
            case "book":
                BooksRoute($arr[3]);
                break;
        }
        exit();
    }
    else
    {
        //variabeln $_POST['formname'] pekar inte på ett formulär i minnet
        echo "<h1>Användaren har ändrat i formuläret</h1>";
        echo "<h2>Händelsen är loggad</h2>";
        echo $_SESSION['REMOTE_ADDR'];
        //Banna användaren??
        exit();
    }
}
else
{
    if (key_exists('form',$_SESSION))
    {
        unset($_SESSION['form']);
        //Formuläret finns i session men inte post nyckeln
        //Då ska formuläret rensas för att det inte används eller användare har påbörjar en editering och sedan ändrat sig.
    }
    //Visa indexsidan
    //Om det finns en session så dödar vi den
    //Något stämmer inte om formname inte finns
    //session_unset();
    //session_destroy();
    //Använd inte else om det finns kod nedanför.

}

//Vanliga routern
if (key_exists('url',$_GET))
{
    $url = explode("/",$_GET['url']);
    switch(strtolower($url[0]))
    {
        case "books":
            if (count($url) == 2)
            {
                BooksRoute($url[1]);
            }
            else
            {
                BooksRoute("");
            }
            break;
        case "user":
            if (count($url) == 2)
            {
                UserRoute($url[1]);
            }
            else
            {
                UserRoute("");
            }
            break;
        case "admin":
            if (count($url) == 2)
            {
                AdminRoute($url[1]);
            }
            else
            {
                AdminRoute("");
            }
            break;
        case "moderator":
            if (count($url) == 2)
            {
                ModeratorRoute($url[1]);
            }
            else
            {
                ModeratorRoute("");
            }
            break;
    }

    switch (strtolower($_GET['url']))
    {
        //Vanliga get ID
        case "showauthor":
            if (key_exists('id',$_GET))
            {
                require_once "controller/Authors.Controller.php";
                $controller = new AuthorsController();
                $safe = $controller->ScrubIndexNumber($_GET['id']);
                $controller->ShowAuthor($safe);
            }
            break;
        case "showbook":
            if (key_exists('id',$_GET))
            {
                require_once "controller/Books.Controller.php";
                $controller = new BooksController();
                $safe = $controller->ScrubIndexNumber($_GET['id']);
                $controller->ShowBook($safe);
            }
            break;
        case "showgenre":
            if (key_exists('id',$_GET))
            {
                require_once "controller/Books.Controller.php";
                $controller = new BooksController();
                $safe = $controller->ScrubIndexNumber($_GET['id']);
                $controller->ShowGenre($safe);
            }
            break;
        case "showreview":
            if (key_exists('id',$_GET))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $safe = $controller->ScrubIndexNumber($_GET['id']);
                $controller->ShowReview($safe);
            }
            break;
        case "showstats":
                require_once "controller/Stats.Controller.php";
                $controller = new StatsController();
                $controller->GeneralStats();
            break;
        case "authors/showall":
            require_once "controller/Authors.Controller.php"; 
            $controller = new AuthorsController();
            $controller->ShowAllAuthors();
            break;   
        case "authors/show";
            if (key_exists('id',$_POST))
            {
                require_once "controller/Authors.Controller.php";
                $controller = new AuthorsController();
                $controller->ShowAuthor($_POST['id']);
            }
            else
            {}
            break;
        case "authors/edit";
            if (key_exists('id',$_POST))
            {
                require_once "controller/Authors.Controller.php";
                $controller = new AuthorsController();
                $controller->EditAuthor($_POST['id']);
            }
            else
            {}
            break;
        case "authors/delete";
            if (key_exists('id',$_POST))
            {
                require_once "controller/Authors.Controller.php";
                $controller = new AuthorsController();
                $controller->DeleteAuthor();
            }
            break;
            case "authors/undelete";
            if (key_exists('id',$_POST))
            {
                require_once "controller/Authors.Controller.php";
                $controller = new AuthorsController();
                $controller->UnDeleteAuthor();
            }
            break;
        case "author/newauthor":
            require_once "controller/Authors.Controller.php";
            $controller = new AuthorsController();
            $controller->NewAuthor();
            break;
        case "author/addauthor":
            require_once "controller/Authors.Controller.php";
            $controller = new AuthorsController();
            $controller->AddAuthor();
            break;
        case "review/newreview":
            if (key_exists('bookId',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->NewReview();
            }
            break;
        case "review/addreview":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->AddReview();
            }
            break;
        case "review/show":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->ShowReview($_POST['id']);
            }
            break;
        case "review/edit":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->EditReview($_POST['id']);
            }
            break;
        case "review/delete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->DeleteReview($_POST['id']);
            }
            break;
        case "review/unflag":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->UnFlagReview($_POST['id']);
            }
            break;
        case "review/undelete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->UnDeleteReview($_POST['id']);
            }
            break;
        case "review/showall":
            require_once "controller/Reviews.Controller.php";
            $controller = new ReviewsController();
            $controller->ShowAllReviews();
            break;
        case "review/usefull":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Reviews.Controller.php";
                $controller = new ReviewsController();
                $controller->WasUsefull();
            }
            break;
        case "review/addcomment":
            require_once "controller/Comments.Controller.php";
            if (key_exists('id',$_POST))
            {
                $controller = new CommentsController();
                $controller->AddComment($_POST['id']);
            }
            break;
        case "review/replycomment":
            require_once "controller/Comments.Controller.php";
            if (key_exists('id',$_POST))
            {
                $controller = new CommentsController();
                $controller->AddReply($_POST['id'],$_SESSION['ReviewId']);
            }
            break;
        case "review/search":
            require_once "controller/Reviews.Controller.php";
            require_once "controller/Books.Controller.php";

            if (key_exists('search',$_POST))
            {
                $controller = new ReviewsController();
                $controller->ShowSearchReview("%".$_POST['search']."%");
            }
            break;
        case "comment/flag":
            if (key_exists('id',$_POST) && key_exists('ReviewId',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->FlagComment($_POST['id']);
            }
            break;
        case "comment/unflag":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->UnFlagComment($_POST['id']);
            }
            break;
        case "comment/delete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->DeleteComment($_POST['id']);
            }
            break;
        case "comment/undelete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->UnDeleteComment($_POST['id']);
            }
            break;
        case "reply/delete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->DeleteReply($_POST['id']);
            }
            break;
        case "reply/undelete":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->UnDeleteReply($_POST['id']);
            }
            break;
        case "reply/flag":
            if (key_exists('id',$_POST) && key_exists('ReviewId',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->FlagReply($_POST['id']);
            }
            break;
        case "reply/unflag":
            if (key_exists('id',$_POST))
            {
                require_once "controller/Comments.Controller.php";
                $controller = new CommentsController();
                $controller->UnFlagReply($_POST['id']);
            }
            break;
        /*ABOUT AND CONTACT PAGE */
        case "about":
            require_once "controller/About.Controller.php";

            $controller = new AboutController();
            $controller->ShowAboutPage();
            break;
        case "contact":
            require_once "controller/Contact.Controller.php";

            $controller = new ContactController();
            $controller->ShowContactPage();
            break;
        default:
            break;
    }
    exit();
}
require_once "controller/Home.Controller.php";
$controller = new HomeController();
$controller->ShowHomePage();
//include_once "views/default.php";

?>
<?php
function ReviewRoute($action)
{
    require_once "controller/Reviews.Controller.php";
    $controller = new ReviewsController();
    switch(strtolower($action))
    {
        case "unflag":
            $controller->UnFlagReview();
            break;
        case "flagged":
            $controller->FlagReview();
            break;
        case "saveeditreview":
            $name = ScrubUserInputs($_POST['formname']);
            if (key_exists('reviewId',$_SESSION['form'][$name]))
            {
                $controller->UpdateReview($_SESSION['form'][$name]['reviewId']);
            }
            break;
    }
}

function AuthorRoute($action)
{
    require_once "controller/Authors.Controller.php";
    $controller = new AuthorsController();
    switch(strtolower($action))
    {
        case "unflag":
            $controller->UnFlagAuthor();
            break;
        case "flagged":
            $controller->FlagAuthor();
            break;
        case "saveeditauthor":
            $controller->UpdateAuthor();
            break;
    }
}

function BooksRoute($action)
{
    require_once "controller/Books.Controller.php";
    $controller = new BooksController();
    switch(strtolower($action))
    {
        case "showall":
            $controller->ShowAllBooks();
            break;
        case "show":
            if (key_exists('id',$_POST))
            {
                $safe = $controller->ScrubIndexNumber($_POST['id']);
                $controller->ShowBook($safe);
            }
            break;
        case "createbook":
            $controller->CreateBook();
            break;
        case "savebook":
            $controller->SaveBook();
        case "delete":
            if (key_exists('id',$_POST))
            {
                $controller->DeleteBook();
            }
            break;
        case "undelete":
            if (key_exists('id',$_POST))
            {
                $controller->UnDeleteBook();
            }
            break;
        case "edit":
            if (key_exists('id',$_POST))
            {
                $safe = $controller->ScrubIndexNumber($_POST['id']);
                $controller->EditBook($safe);
            }
            break;
        case "saveeditbook":
            
            $name = ScrubUserInputs($_POST['formname']);
            if (key_exists('bookId',$_SESSION['form'][$name]))
            {
                $controller->UpdateBook($_SESSION['form'][$name]['bookId']);
            }
            break;
        case "creategenre":
            $controller->CreateGenre();
            break;
        case "savegenre":
            $controller->SaveGenre();
            break;
        case "editgenre":
            if (key_exists('id',$_POST))
            {
                $safe = $controller->ScrubIndexNumber($_POST['id']);
                $controller->EditGenre($safe);
            }
            
            break;
        case "revivegenre":
            if (key_exists('id',$_POST))
            {
                $controller->ReviveGenre();
            }
            break;
        case "saveeditgenre":
            $name = ScrubUserInputs($_POST['formname']);
            if (key_exists('genreId',$_SESSION['form'][$name]))
            {
                $controller->UpdateGenre($_SESSION['form'][$name]['genreId']);
            }
            break;
        case "deletegenre":
            if (key_exists('id',$_POST))
            {
                $controller->HideGenre();
            }
            break;
        case "showallgenre":
            $controller->ShowAllGenre();
            break;
        case "search":
            $safe = ScrubUserInputs($_POST['search']);
            $controller->ShowSearchBook("%".$safe."%");
            break;
        case "searchgenre":
            $safe = ScrubUserInputs($_POST['genre']);
            $controller->ShowSearchBook("%".$safe."%");
            break;
        case "searchauthor":
            $safe = ScrubUserInputs($_POST['author']);
            $controller->ShowSearchBook("%".$safe."%");
            break;
        default:
            break;
    }
}

function UserRoute($action)
{
    require_once "controller/User.Controller.php";
    $controller = new UserController();
    switch(strtolower($action))
    {
        case "create"://Visar CreateUserForm
            $controller->CreateUser();
            break;
        case "saveuser": //Spara CreateUserForm
            $controller->SaveUser();
        case "loginpage":
            $controller->LoginPage(); //Visar loginformuläret
            break;
        case "loginuser":
            $controller->Login(); //kontrollerar loginformulär
            break;
        case "logoutuser":
            $controller->Logout(); //Loggar ut och förstör session
            break;
    }
}
function AdminRoute($action)
{
    require_once "controller/Admin.Controller.php";
    $controller = new AdminController();
    switch ($action)
    {
        case "":
            $controller->AdminPanel();
            break;
        case "showall":
            $controller->ShowAllUsers();
            break;
        case "showuserform":
            if (key_exists('id',$_POST))
            {
                $controller->ShowUser();
            }
            break;
        case "addrolestouser":
            if (key_exists('formname',$_POST))
            {
                if (isset($_SESSION['form'][$_POST['formname']]))
                {
                    $controller->AddUserRole();
                }
            }
            break;
        case "removerolefromuser":
            if (key_exists('formname',$_POST))
            {
                if (isset($_SESSION['form'][ScrubUserInputs($_POST['formname'])]))
                {
                    $controller->RemoveUserRoleFromUser();
                }
            }
            break;
        default:
            break;
    }
}

function ModeratorRoute($action)
{
    require_once "controller/Moderator.Controller.php";
    $controller = new ModeratorController();
    switch ($action)
    {
        case "":
            $controller->ShowModeratorPage();
            break;
        default:
            break;
    }
}
?>