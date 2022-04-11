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
?>