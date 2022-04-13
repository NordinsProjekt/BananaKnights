<?php
session_start();
const prefix = "/BananaKnights/";
$fakeSession = array (
    "is_logged_in" => true, "UserID" => 1, "Role" => "User"
);
?>

<?php
//Delar upp url:en för funktioner inom api:et
//Kontrollerar så token är giltig
//var_dump($_GET);
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
            AdminRoute("");
            break;
    }

    switch (strtolower($_GET['url']))
    {
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
                $controller->DeleteAuthor($_POST['id']);
            }
            else
            {}
            break;
        case "author/newauthor":
            require_once "controller/Authors.Controller.php";
            $controller = new AuthorsController();
            $controller->NewAuthor();
            break;
        case "author/addauthor":
            require_once "controller/Authors.Controller.php";
            $controller = new AuthorsController();
            $controller->AddAuthor($fakeSession);
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
                require_once "controller/Books.Controller.php";
                $controller = new ReviewsController();
                $controller->AddReview();
            }
            break;
        case "review/showall":
            require_once "controller/Reviews.Controller.php";
            $controller = new ReviewsController();
            $controller->ShowAllReviews();
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
                $controller->ShowBook();
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
        case "showgenre":
            if (key_exists('id',$_POST))
            {
                $controller->ShowGenre();
            }
            break;
        case "editgenre":
            if (key_exists('id',$_POST))
            {
                $controller->EditGenre();
            }
            break;
        case "updategenre":
            if (key_exists('id',$_POST))
            {
                $controller->UpdateGenre();
            }
            break;
        case "deletegenre":
            if (key_exists('id',$_POST))
            {
                $controller->DeleteGenre();
            }
            break;
        case "showallgenre":
            $controller->ShowAllGenre();
            break;
        case "search":
            $controller->ShowSearchBook("%".$_POST['search']."%");
            break;
        default:
            break;
    }
    exit();
}

function UserRoute($action)
{
    $fakeSession = array (
        "is_logged_in" => true, "UserID" => 1, "Role" => "User"
    );
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
    $fakeSession = array (
        "is_logged_in" => true, "UserID" => 1, "Role" => "User"
    );
    require_once "controller/Admin.Controller.php";
    $controller = new AdminController();
    switch ($action)
    {
        case "":
            $controller->Show();
            break;
    }
    exit();
}
?>