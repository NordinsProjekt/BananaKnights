<?php
session_start();
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
    switch(strtolower($_GET['url']))
    {
        //Route books/showall
        case "books/showall":
            require_once "controller/Books.Controller.php";
            $controller = new BooksController();
            $controller->ShowAllBooks();
            break;
        //Route books/show?id=1
        case "books/show";
            if (key_exists('id',$_GET))
            {
                require_once "controller/Books.Controller.php";
                $controller = new BooksController();
                $controller->ShowBook($_GET['id']);
            }
            else
            {}
        break;

        case "books/create":
            require_once "controller/Books.Controller.php";
            $controller = new BooksController();
            $controller->Create();
            break;
        //Hit kommer man från SparaBok formuläret
        case "books/savebook":
            require_once "controller/Books.Controller.php";
            $controller = new BooksController();
            $controller->SaveBook($fakeSession);
            break;
        default:
        break;
    }
}
else
{
    exit();
}
?>