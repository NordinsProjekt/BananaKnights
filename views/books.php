<?php

function CreateNewBook($arrGenre,$arrAuthor)
{
    //Skapa Bok formuläret
    $text = "<h1>Skapa ny bok</h1>";
    $text .= "<form method='post' action='".prefix."books/savebook' enctype='multipart/form-data'>";
    $text .= "<table><tr><th></th><th></th></tr>";
    $text .= "<tr> <td><label for='txtBookTitle'>Titel</label></td> <td><input type='text' id='txtBookTitle' name='BookTitle' pattern='.{1,}' placeholder='Bokens titel'/></td> </tr>";
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
    $text .= "<tr><td><label for='pubYear'>Utgivningsdatum</label></td> <td><input type='text' size='4' 
    id='pubYear' name='BookYear' pattern ='[0-9]{0,4}' placeholder='ex 1986'/></td>";
    $text .= "<tr> <td><label for='txtBookDescription'>Beskrivning</label></td> 
              <td><textarea id='txtBookDescription' name='BookDescription' rows='5' cols='30' 
              placeholder='Beskrivande text, minst 5 tecken' pattern='.{5,}'></textarea></td> </tr>";
    $text .= "<tr> <td><label for='txtBookISBN'>ISBN</label></td> <td><input type='text' id='txtBookISBN' name='BookISBN'
     placeholder='ISBN nummer' /></td> </tr>";
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
        $text .= "<tr> <td><label for='txtBookGenre'>Genre</label></td> <td><input type='text' id='txtBookGenre' 
        name='BookGenre' pattern='.{3,}' placeholder='Genre namn'/></td> </tr>";
        $text .= "<tr> <td><label for='txtGenreDescription'>Beskrivning</label></td> 
                  <td><textarea id='txtGenreDescription' name='GenreDescription' rows='5' cols='30' 
                  pattern='.{5,}' placeholder='Minst 5 tecken'></textarea></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveGenre' value='Spara' /></td> </tr>";
        $text .= "</table></form>";
        return $text;
}

function DeleteGenre()
{

}

function ShowBook($book,$imageLink,$role)
{
    $text = "<h1>Visa enskild bok</h1>";
    $text .= "<h2>".$book['Title']."</h2>";
    $text .= "<img src='".$imageLink."' />";
    $text .= "<p><b>Författare:</b> <a href='".prefix."showauthor?id=".$book['AuthorId']."'>".$book['AuthorName']."</a><br />";
    $text .= "<b>Genre:</b> " .$book['GenreName']."<br />";
    $text .= "<b>Utgivningsår:</b> ".$book['PublicationYear']."<br />";
    $text .= "<b>ISBN: </b>".$book['ISBN']."<br /></p>";
    $text .= "<h3>Beskrivning</h3>";
    $text .= "<p>".$book['Description']."</p>";
    if ($role == "User" || $role == "Admin")
    {
        $text .= "<form method='post' action='".prefix."review/newreview' >
        <button type='submit' name='bookId'value='".$book['Id']."'>Skriv recension</button></form>";
    }

    return $text;
}

function ShowAllBooks($arr,$role)
{
    $text = "<h1>Visa alla böcker</h1>";
    if ($role == "Admin")
    {
        $text .= "<table><tr> <th>Titel</th> <th>År</th> <th>Beskrivning</th> <th>Genre</th> <th>Författare</th> <th>Visa</th>
        <th>Edit</th><th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Titel</th> <th>År</th> <th>Beskrivning</th> <th>Genre</th> <th>Författare</th> <th>Visa</th></tr>";
    }
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['PublicationYear']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['GenreName']."</td>";
        $text.= "<td>".$row['AuthorName']."</td>";
        $text.= "<td><form method='post' action='".prefix."books/show'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."books/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."books/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if ($role == "Admin")
    {
        $text.= "</table><form method='post' action='".prefix."books/createbook'><button type='submit'>Skapa ny bok</button></form>";
    }
    return $text;
}

function ShowAllGenre($arr,$role)
{
    $text = "<h1>Visa alla genre</h1>";
    if ($role == "Admin")
    {
        $text .= "<table><tr> <th>Namn</th> <th>Beskrivning</th> <th>Skapad</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Namn</th> <th>Beskrivning</th> <th>Visa</th></tr>";
    }
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Name']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."books/showgenre'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."books/editgenre'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."books/deletegenre'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if ($role == "Admin")
    {
        $text.= "</table><form method='post' action='".prefix."books/creategenre'><button type='submit'>Skapa ny genre</button></form>";
    }
    return $text;
}

function ShowSearch()
{
    
}

?>