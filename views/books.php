<?php

function CreateNewBook($arrGenre,$arrAuthor)
{
    //Skapa Bok formuläret
    $text = "<h1>Skapa ny bok</h1>";
    $text .= "<form method='post' action='savebook'>";
    $text .= "<table><tr><th></th><th></th></tr>";
    $text .= "<tr> <td><label for='txtBookTitle'>Titel</label></td> <td><input type='text' id='txtBookTitle' name='BookTitle' /></td> </tr>";
    $text .= "<tr> <td><label for='selAuthor'>Författare</label></td> <td><select name='BookAuthor' id='selAuthor'>";
    foreach ($arrAuthor as $key => $value) {
        $text.= "<option value='1'>".$value."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr> <td><label for='selGenre'>Genre</label></td> <td><select name='BookGenre' id='selGenre'>";
    foreach ($arrGenre as $key => $value) {
        $text.= "<option value='1'>".$value."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr> <td><label for='txtBookDescription'>Beskrivning</label></td> 
              <td><textarea id='txtBookDescription' name='BookDescription' rows='5' cols='30'></textarea></td> </tr>";
    $text .= "<tr> <td><label for='txtBookISBN'>ISBN</label></td> <td><input type='text' id='txtBookISBN' name='BookISBN' /></td> </tr>";
    $text .= "<tr> <td><label for='txtBookPicture'>Bild</label></td> <td><input type='file' id='txtBookPicture' name='BookPicture' /></td> </tr>";
    $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveBook' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
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
    var_dump($arr);
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