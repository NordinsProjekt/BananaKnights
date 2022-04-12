<?php

function FelMeddelande($text)
{
    $text = "";
    return $text;
}

function StartPage($titel)
{
    $text= "";
    $text .= "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' type='text/css' href='/bananaknights/css/style.css' media='screen' />
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
        <title>". $titel ."</title>
    </head>
    <body>";
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


function IndexNav()
{
    $text = <<<XYZ
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Coolbooks</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
                </li>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
        </nav>
    XYZ;
    echo $text;
}


function IndexTop()
{
    require_once "controller/Books.Controller.php";
    require_once "model/Books.Model.php";
    require_once "views/books.php";

    $rnd = rand(1,4);

    $model = new BooksModel;
    $result = $model->GetBook($rnd);

    foreach ($result as $value) {
        $bookinfo[] = $value;
    }

    $text = "<div class='card mb-3 center-div' style='max-width: 100%;'>
        <div class='row g-0'>
        <div class='col-md-4'>
            <img src='' class='img-fluid rounded-start' alt='book photo'>
        </div>
        <div class='col-md-8'>
            <div class='card-body'>
            <h5 class='card-title'>" . $bookinfo[1] . "</h5>
            <p class='card-text'>" . $bookinfo[3] ."</p>
            <p class='card-text'>" . $bookinfo[4] ."</p>
            <p class='card-text'>" . $bookinfo[5] ."</p>
            <p class='card-text'>" . $bookinfo[2] ."</p>
            <p class='card-text'><small class='text-muted'>tillagd den 2022-04-11 </small></p>
            </div>
        </div>
        </div>
        </div>";   
    echo $text;
}


function IndexCards()
{
    require_once "controller/Books.Controller.php";
    require_once "model/Books.Model.php";
    require_once "views/books.php";

    $model = new BooksModel();
    $result = $model->GetAllBooks();
    foreach ($result as $value) {
        $books[] = $value;
    }

    print_r($books);

    $text = 
    "<div class='card' style='width: 18rem;'>
    <img src='' class='card-img-top' alt='book photo'>
    <div class='card-body'>
      <h5 class='card-title'>" . $books[0]["Title"] . "</h5>
      <p class='card-text'>" .  $books[0]["GenreName"] . "</p>
      <p class='card-text'>" .  $books[0]["AuthorName"] . "</p>
      <a href='#' class='btn btn-primary'>Läs mer</a>
    </div>
  </div>";
echo $text;
}
?>