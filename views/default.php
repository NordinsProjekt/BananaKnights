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
        <link rel='stylesheet' type='text/css' href='/BananaKnights/css/style.css' media='screen' />
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

?>