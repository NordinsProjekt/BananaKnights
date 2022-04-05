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
        case "books/showall":
            require_once "controller/Books.Controller.php";
            $controller = new BooksController();
            $controller->ShowAll();
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