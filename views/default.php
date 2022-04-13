<?php

function StartPage($titel)
{
    $text = "<!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='".prefix."css/style.css' media='screen' />
    <link href='".prefix."css/bootstrap.min.css' rel='stylesheet'>
    <script src='".prefix."js/bootstrap.bundle.min.js'></script>
    <title>". $titel ."</title>
    </head>";
    return $text;
}

function EndPage()
{
    $text= "";
    $text .= "</body></html>";
    return $text;
}
function NavigationPage()
{
    $text = "";
    $text .= "<header><a href='".prefix."books/showall'>Visa alla böcker</a>";
    $text .= "<a href='".prefix."authors/showall'>Visa alla författare</a>";
    $text .= "<a href='".prefix."books/showallgenre'>Visa alla genre</a>";
    //$text .= "<a href='".$prefix."books/createbook'>Skapa ny bok</a>";
    //$text .= "<a href='".$prefix."books/creategenre'>Skapa ny genre</a>";
    $text .= "<a href='".prefix."user/create'>Skapa användare</a>";
    if (isset($_SESSION['is_logged_in']))
    {
        $text .= "<a href='".prefix."user/logoutuser'>Logga ut</a>";
    }
    else
    {
        $text .= "<a href='".prefix."user/loginpage'>Logga in</a>";
    }
    $text .= "<a href='".prefix."admin'>Adminsidan</a>";
    $text .= "</header>";
    return $text;
}

function ReviewNavigation()
{
    $text = "<div>";
    $text .= "<a href='newreview'>Skriv en recension</a>";
    $text .= "<a href='readreviews'>Visa alla recensioner</a>";
    $text .= "<div>";
    return $text;
}


function IndexNav($role,$username)
{
    $text = "
    <body>
            <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
        <div class='container-fluid'>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarTogglerDemo01' aria-controls='navbarTogglerDemo01' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarTogglerDemo01'>
            <a class='navbar-brand' href='".prefix."'>Coolbooks</a>
            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                <li class='nav-item'>
                <a class='nav-link active' aria-current='page' href='".prefix."'>Home</a>
                </li>
                <li class='nav-item'>
                <a class='nav-link' href='#'>About</a>
                </li>
                <li class='nav-item'>
                <a class='nav-link' href='#'>Contact</a>
                </li>
            </ul>
            <form class='d-flex me-auto' method='post' action='".prefix."books/search'>
            <input class='form-control me-2 mt-3' type='search' placeholder='Search' aria-label='Search' name='search' />
            <button class='btn btn-outline-success mt-3' type='submit'>Search</button>
        </form>
            <ul class='navbar-nav mb-2 mb-lg-0'>
                
                ";
                if ($role != "")
                {
                    $text .= "<li><a class='nav-link' href='".prefix."user/profile'>".$username."</a></li>";
                    $text .= "<li><a class='nav-link' href='".prefix."user/logoutuser'>Logout</a>
                    </li>";
                }
                else
                {
                    $text .= "<a class='nav-link' href='".prefix."user/loginpage'>Login</a></li>";
                    $text .= "<li><a class='nav-link' href='".prefix."user/create'>Register</a></li>";
                }

            $text .= "
            </ul>
            </div>
        </div>
        </nav>
    ";
    echo $text;
}


function IndexTop()
{
    require_once "controller/Books.Controller.php";
    require_once "model/Books.Model.php";
    require_once "views/books.php";

    $model = new BooksModel;
    $result = $model->GetAllBooks();
    
    $rnd = rand(0,count($result)-1);


    foreach ($result as $value) {
        $bookinfo[] = $value;
    }

    if (file_exists("img/books/". $bookinfo[$rnd]['ImagePath']))
    {
        $pictures = scandir("img/books/". $sorted[$rnd]['ImagePath']);
        $imageLink = prefix."img/books/". $sorted[$rnd]['ImagePath'] ."/". $pictures[2];
    }
    else
    {
        $imageLink = prefix."img/books/noimage.jpg";
    }
    $text = "<br><div class='card'>
        <div class='row'><br>
        <div class='col'><br>

            <img src='" . $imageLink . "' class='img-fluid rounded-start' alt='book photo' height='100px'>
        </div>
        <div class='col-md-10'>
            <div class='card-body'><br><br>
            <h5 class='card-title'>" . $bookinfo[$rnd]["Title"] . "</h5>
            <p class='card-text'>" . $bookinfo[$rnd]["Description"] ."</p>
            <p class='card-text'>" . $bookinfo[$rnd]["GenreName"] ."</p>
            <p class='card-text'>" . $bookinfo[$rnd]["AuthorName"] ."</p>
            <p class='card-text'><small class='text-muted'>tillagd den " . $bookinfo[$rnd]["Created"] . "</small></p>
            </div>
        </div>
        </div>
        </div><br><br>";   
    echo $text;
}


function IndexCards()
{
    require_once "controller/Books.Controller.php";
    require_once "model/Books.Model.php";
    require_once "views/books.php";

    $model = new BooksModel();
    $sorted = $model->GetAllBooksSorted();
    
    $text = 
    "<div class='container-fluid'>
    <div class='row' style='text-align: center'>
    <br>";
    for($i = 0; $i < count($sorted); $i++)
    {
        if (file_exists("img/books/". $sorted[$i]['ImagePath']))
        {
            $pictures = scandir("img/books/". $sorted[$i]['ImagePath']);
            $imageLink = prefix."img/books/". $sorted[$i]['ImagePath'] ."/". $pictures[2];
        }
        else
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
    $text .= 
    "<div class='col text-white bg-secondary' style='border-radius: 8px'><br>
    <img src='" . $imageLink . "' alt='book bild' height='100px'><br>"
    . $sorted[$i]["Title"] . "<br>"
    . $sorted[$i]["Name"] . "<br><br>
    <form method='post' action='".prefix."books/show'><button type='submit' class='btn btn-primary' name='id' value='".$sorted[$i]['Id']."'>Läs mer</button></form><br>
    </div><br>";
    }
    $text .= "</div></div><br>";

    echo $text;
}
?>