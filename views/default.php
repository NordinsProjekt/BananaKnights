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
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>
    <script src='".prefix."js/OpenFilterList.js'></script>
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
    <body style='max-width: 100%; overflow-x: hidden;'>
        <nav class='navbar navbar-expand-lg navbar-dark bg-dark' style='box-shadow: 0px 5px 10px -6px black;'>
        <div class='container-fluid'>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarTogglerDemo01' aria-controls='navbarTogglerDemo01' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarTogglerDemo01'>
            <a class='navbar-brand' href='".prefix."'>Coolbooks</a>
            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                <li class='nav-item'>
                <a class='nav-link' href='".prefix."'>Home</a>
                </li>
                <li class='nav-item'>
                <a class='nav-link' href='".prefix."about'>About</a>
                </li>
                <li class='nav-item'>
                <a class='nav-link' href='".prefix."contact'>Contact</a>
                </li>
            </ul>
            <form class='d-flex me-auto' method='post' action='".prefix."books/search'>
            <input class='form-control me-2 mt-2 mb-2' type='search' placeholder='Search' aria-label='Search' name='search' />
            <button class='btn btn-outline-success mt-2 mb-2' type='submit'>Search</button>
            </form>";
            $text .="<ul class='navbar-nav mb-2 mb-lg-0'>";
            if (str_contains($role,"Admin") || str_contains($role,"Moderator"))
            {
                $text .= "<li><a class='nav-link' href='".prefix."books/showall'>Böcker</a></li>";
                $text .= "<li><a class='nav-link' href='".prefix."review/showall'>Recensioner</a></li>";
                $text .= "<li><a class='nav-link' href='".prefix."authors/showall'>Författare</a></li>";
                $text .= "<li><a class='nav-link' href='".prefix."books/showallgenre'>Genre</a></li>";
            }
            if (str_contains($role,"Moderator"))
            {
                $text .= "<li><a class='nav-link' href='".prefix."moderator'>Mod panel</a></li>";
            }
            if (str_contains($role,"Admin"))
            {
                $text .= "<li><a class='nav-link' href='".prefix."admin'>Adminpanel</a></li>";
            }
                if ($role != "")
                {
                    $text .= "<li><a class='nav-link' href='".prefix."user/profile'>".$username." profil</a></li>";
                    $text .= "<li><a class='nav-link' href='".prefix."user/logoutuser'>Logout</a></li>";
                }
                else
                {
                    $text .= "<li><a class='nav-link' href='".prefix."user/loginpage'>Login</a></li>";
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
        $pictures = scandir("img/books/". $bookinfo[$rnd]['ImagePath']);
        if (empty($pictures[2]))
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
        else
        {
            $imageLink = prefix."img/books/". $result[$rnd]['ImagePath'] ."/". $pictures[2];
        }
    }
    else
    {
        $imageLink = prefix."img/books/noimage.jpg";
    }
    $text = "<br><div class='card' style='width:50%; float: left; padding: 0 0 0 50px'>
        <div class='row align-items-center'><br>
        <div class='col-4'><br>
            <img src='" . $imageLink . "' class='img-fluid rounded-start' alt='book photo' height='800rem' />
            <form method='post' action='".prefix."showbook?id=".$bookinfo[$rnd]['Id']."'><button type='submit' class='btn btn-outline-primary' style='margin-top: 20px;'>Läs mer</button></form>
        </div>
        <div class='col-6' style='padding: 0 0 60px 0;'>
            <div class='card-body'><br><br>
            <h5 class='card-title'>" . $bookinfo[$rnd]["Title"] . "</h5>
            <p class='card-text'>" . $bookinfo[$rnd]["Description"] ."</p>
            <p class='card-text'>" . $bookinfo[$rnd]["GenreName"] ."</p>
            <p class='card-text'>" . $bookinfo[$rnd]["AuthorName"] ."</p>
            <p class='card-text'><small class='text-muted'>tillagd den " . $bookinfo[$rnd]["Created"] . "</small></p>
            </div>
        </div>
        </div>
        </div><br>"; 

    echo $text;
}


function HomeNavMeny()
{
    $text = "";
    $text.= "<div class='container bg-dark' style='padding: 50px 0 0 0;'>";
    $text.= "<div class='list-group'>
            <a class='btn btn-outline-primary' href='".prefix."books/showall'>Visa alla böcker</a>
            <a style='margin-top:10px;' class='btn btn-outline-primary' href='".prefix."books/top5'>Topplista</a>
            <a style='margin-top:10px;' class='btn btn-outline-primary' href='".prefix."'>Sämst betyg</a>
            <a style='margin-top:10px;' class='btn btn-outline-primary' href='".prefix."'quiz/showall>Visa alla quiz</a>
    </div>";
    $text.= "</div>";

    echo $text;
}


function IndexCards()
{
    require_once "controller/Books.Controller.php";
    require_once "model/Books.Model.php";
    require_once "views/books.php";

    $model = new BooksModel();
    $sorted = $model->GetAllBooksSorted();
    
    $text = "";
    $text.= "<div class='container' style='max-width: 100%; padding: 50px 0 0 30px; float:left;'>";
    $text.="<div class='row' style='text-align: left;'><br>";
    $text .= "<hr>";

    for($i = 0; $i < count($sorted); $i++)
    {
        if (file_exists("img/books/". $sorted[$i]['ImagePath']))
        {
            $pictures = scandir("img/books/". $sorted[$i]['ImagePath']);
            if (empty($pictures[2]))
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            else
            {
                $imageLink = prefix."img/books/". $sorted[$i]['ImagePath'] ."/". $pictures[2];
            }
        }
        else
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
    $text .= 
    "<div class='col text-white' style='border-radius: 8px;'><br>
    <img src='" . $imageLink . "' alt='book bild' height='200rem' width='140rem'><br>
    <a style='padding:0;' href='".prefix."showbook?id=".$sorted[$i]['Id']."'>". $sorted[$i]["Title"] . "</a><br>
    <small>". $sorted[$i]["Firstname"] ." ". $sorted[$i]["Lastname"] . "</small><br>
    <form method='post' action='".prefix."showbook?id=".$sorted[$i]['Id']."' style='padding-top:10px;'><button type='submit' class='btn btn-outline-primary'>Läs mer</button></form><br>
    </div><br>";
    }
    $text .= "</div></div><br>";

    echo $text;
}

function IndexCardsV2($sorted)
{
    $text = "";
    $text.= "<div class='container'>";
    $text.="<div class='row' style='text-align: left; --bs-gutter-x:4.5rem;'><br>";

    for($i = 0; $i < count($sorted); $i++)
    {
        if (file_exists("img/books/". $sorted[$i]['ImagePath']))
        {
            $pictures = scandir("img/books/". $sorted[$i]['ImagePath']);
            if (empty($pictures[2]))
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            else
            {
                $imageLink = prefix."img/books/". $sorted[$i]['ImagePath'] ."/". $pictures[2];
            }
        }
        else
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
    $text .= 
    "<div class='col text-white' style='border-radius: 8px;'><br>
    <img src='" . $imageLink . "' alt='book bild' height='250rem'><br>
    <a style='padding:0;' href='".prefix."showbook?id=".$sorted[$i]['Id']."'>". $sorted[$i]["Title"] . "</a><br>
    <small>". $sorted[$i]["AuthorName"] . "</small><br />
    <small>". $sorted[$i]["GenreName"] . "</small><br />
    <small>Rating: ".$sorted[$i]['Rating']." av 5</small><br />
    <form method='post' action='".prefix."showbook?id=".$sorted[$i]['Id']."' style='padding-top:10px;'><button type='submit' class='btn btn-outline-primary'>Läs mer</button></form><br>
    </div><br>";

        if($i == 4)
        {
            $text.="</div>";
            $text.="<div class='row' style='text-align: left; padding-top: 2rem; --bs-gutter-x:4.5rem;'><br>";
        }

    
    }
    $text .= "</div></div><br>";
    return $text;
}

function IndexCardsProfile($sorted)
{
    $text = "";
    $text.= "<div class='container'>";
    $text.="<div class='row' style='text-align: left; --bs-gutter-x:4.5rem;'><br>";

    for($i = 0; $i < count($sorted); $i++)
    {
        if (file_exists("img/books/". $sorted[$i]['ImagePath']))
        {
            $pictures = scandir("img/books/". $sorted[$i]['ImagePath']);
            if (empty($pictures[2]))
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            else
            {
                $imageLink = prefix."img/books/". $sorted[$i]['ImagePath'] ."/". $pictures[2];
            }
        }
        else
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
    $text .= 
    "<div class='col text-black' style='border-radius: 8px;'><br>
    <img src='" . $imageLink . "' alt='book bild' height='250rem'><br>
    <a style='padding:0;' href='".prefix."showbook?id=".$sorted[$i]['Id']."'>". $sorted[$i]["Title"] . "</a><br>
    <small>". $sorted[$i]["AuthorName"] . "</small><br />
    <small>". $sorted[$i]["GenreName"] . "</small><br />
    <form method='post' action='".prefix."showbook?id=".$sorted[$i]['Id']."' style='padding-top:10px;'><button type='submit' class='btn btn-outline-primary'>Läs mer</button></form><br>
    </div><br>";

        if($i == 4)
        {
            $text.="</div>";
            $text.="<div class='row' style='text-align: left; padding-top: 2rem; --bs-gutter-x:4.5rem;'><br>";
        }

    
    }
    $text .= "</div></div><br>";
    return $text;
}

function SearchReview()
{
    $text = "<br><br><form class='me-auto' method='post' action='".prefix."review/search'>";
    $text .= "<input type='search' placeholder='Review title....' name='search'/>";
    $text .= "<button type='submit'>Search Review</button>";
    $text .= "</form><br>";
    $text .= "<form class='me-auto' method='post' action='".prefix."review/showall'>";
    $text .= "<button type='submit'>Visa alla</button>";
    $text .= "</form>";
    echo $text;
}
?>