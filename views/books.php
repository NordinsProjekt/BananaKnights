<?php

function CreateNewBook($arrGenre,$arrAuthor)
{
    //Skapa Bok formuläret
    $text = "<h1>Skapa ny bok</h1>";
    $text .= "<form method='post' action='".prefix."books/savebook' enctype='multipart/form-data'>";
    $text .= "<table><tr><th></th><th></th></tr>";
    $text .= "<tr> <td><label for='txtBookTitle'>Titel</label></td> <td><input type='text' id='txtBookTitle' name='BookTitle' /></td> </tr>";
    $text .= "<tr> <td><label for='selAuthor'>Författare</label></td> <td><select name='BookAuthor' id='selAuthor'>";
    foreach ($arrAuthor as $key => $value) {
        $text.= "<option value='".$value['Id']."'>".$value['Firstname']." ".$value['Lastname']."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr> <td><label for='selGenre'>Genre</label></td> <td><select name='BookGenre' id='selGenre'>";
    foreach ($arrGenre as $key => $value) {
        $text.= "<option value='" . $value['Id'] . "'>".$value['Name']."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr><td><label for='pubYear'>Utgivningsdatum</label></td> <td><input type='text' size='4' id='pubYear' name='BookYear' /></td>";
    $text .= "<tr> <td><label for='txtBookDescription'>Beskrivning</label></td> 
              <td><textarea id='txtBookDescription' name='BookDescription' rows='5' cols='30'></textarea></td> </tr>";
    $text .= "<tr> <td><label for='txtBookISBN'>ISBN</label></td> <td><input type='text' id='txtBookISBN' name='BookISBN' /></td> </tr>";
    $text .= "<tr> <td><label for='txtBookPicture'>Bild</label></td> <td><input type='file' id='txtBookPicture' name='BookPicture' /></td> </tr>";
    $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveBook' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
    return $text;
}

function CreateNewGenre()
{
        //Skapa Genre formulär
        $text = "<h1>Skapa ny genre</h1>";
        $text .= "<form method='post' action='".prefix."books/savegenre'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtBookGenre'>Genre</label></td> <td><input type='text' id='txtBookGenre' name='BookGenre' /></td> </tr>";
        $text .= "<tr> <td><label for='txtGenreDescription'>Beskrivning</label></td> 
                  <td><textarea id='txtGenreDescription' name='GenreDescription' rows='5' cols='30'></textarea></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveGenre' value='Spara' /></td> </tr>";
        $text .= "</table></form>";
        return $text;
}

function DeleteGenre()
{

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
    global $prefix;
    $text = "<h1>Visa alla böcker</h1>";
    $text .= "<table><tr> <th>Titel</th> <th>År</th> <th>Beskrivning</th> <th>Genre</th> <th>Författare</th> <th>Visa</th></tr>";
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['PublicationYear']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['GenreName']."</td>";
        $text.= "<td>".$row['AuthorName']."</td>";
        $text.= "<td><form method='post' action='".prefix."books/show'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
        $text.= "</tr>";
    }
    return $text;
}
function ShowAllBooksAdmin($arr)
{
    $text = "<h1>Visa alla böcker</h1>";
    $text .= "<table><tr> <th>Titel</th> <th>År</th> <th>Beskrivning</th> <th>Genre</th> 
    <th>Författare</th> <th>Visa</th> <th>Ändra</th> <th>Radera</th> </tr>";
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['PublicationYear']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['GenreName']."</td>";
        $text.= "<td>".$row['AuthorName']."</td>";
        $text.= "<td><form method='post' action='". prefix ."books/show'><button type='submit' name='id' value='".$row['Id']."'>Visa</button>
        </form></td>";
        $text.= "<td><form method='post' action='".prefix."books/edit'><button type='submit' name='id' value='".$row['Id']."'>Visa</button>
        </form></td>";
        $text.= "<td><form method='post' action='".prefix."books/delete'><button type='submit' name='id' value='".$row['Id']."'>Visa</button>
        </form></td>";

        $text.= "</tr>";
    }
    return $text;
}
?>