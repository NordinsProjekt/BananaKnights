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
if (isset($_POST['formname']))
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
        //Formuläret finns inte
        //Banna användaren??
        exit();
    }
}
else
{
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
                $controller->ShowReview();
            }
            break;
        case "review/edit":
            if (key_exists('id',$_POST))
            {}
            break;
        case "review/delete":
            if (key_exists('id',$_POST))
            {}
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
        default:
          break;
    }
}
else
{
    require_once "controller/Home.Controller.php";
    $controller = new HomeController();
    $controller->ShowHomePage();
    //include_once "views/default.php";
}
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
                $controller->EditBook();
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
        default:
            break;
    }
    exit();
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
    exit();
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
    exit();
}
?>