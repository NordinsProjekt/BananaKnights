<?php
session_start();
?>

<?php
//Delar upp url:en för funktioner inom api:et
//Kontrollerar så token är giltig
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
        default:
        break;
    }
}
else
{
    exit();
}
?>