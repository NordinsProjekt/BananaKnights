<?php
session_start();
const prefix = "/bananaknights/";
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
        if (key_exists('id',$_GET))
        {
            require_once "controller/Authors.Controller.php";
            $controller = new AuthorsController();
        $controller->ShowAuthor($_GET['id']);
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
            echo "inne i authors";
            $controller->AddAuthor($fakeSession);
            break;

        case "review/newreview":
            require_once "controller/Reviews.Controller.php";
            $controller = new ReviewsController();
            $controller->NewReview();
            break;
        case "review/addreview":
            require_once "controller/Reviews.Controller.php";
            require_once "controller/Books.Controller.php";
            $controller = new ReviewsController();
            $controller->AddReview(/*$_GET['id']*/1, $fakeSession);
            break;


        default:
          break;
    }
}
else
{
    echo "<h1>Detta är startsidan</h1>";
    exit();
}
?>
<?php
function BooksRoute($action)
{
    $fakeSession = array (
        "is_logged_in" => true, "UserID" => 1, "Role" => "User"
    );
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
                $controller->ShowBook($_POST['id']);
            }
            break;
        case "createbook":
            $controller->CreateBook();
            break;
        case "savebook":
            $controller->SaveBook($fakeSession);
        case "delete":
            if (key_exists('id',$_POST))
            {
                $controller->DeleteBook($_POST['id']);
            }
            break;
        case "creategenre":
            $controller->CreateGenre();
            break;
        case "savegenre":
            $controller->SaveGenre($fakeSession);
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