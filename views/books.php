<?php

function CreateNewBook()
{
    $text = "";
    $text = "<p>Här lägger vi ett formulär för att skapa en bok</p>";
    return $text;
}

function ShowBook($book)
{
    $text = "<h1>Visa enskild bok</h1>";
    foreach ($book as $key => $value) {
        $text .= "<p>".$key.": ".$value."</p>";
    }
    return $text;
}

function ShowAllBooks($arr)
{
    $text = "<h1>Visa alla böcker</h1>";
    $text .= "<table><tr> <th>Titel</th> <th>Beskrivning</th> <th>Genre</th> <th>Författare</th> <th>Visa</th></tr>";
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['GenreName']."</td>";
        $text.= "<td>".$row['AuthorName']."</td>";
        $text.= "<td><form><input type='submit' name='visaBok' value='Visa' />
        <input type='hidden' name='bok' value='" .$row['Id'] . " />'</form></td>";
        $text.= "</tr>";
    }
    return $text;
}
?>