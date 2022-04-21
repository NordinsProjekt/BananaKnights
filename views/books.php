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

function EditGenre($genre,$role)
{
    $text = "";
    if (str_contains($role,"Admin"))
    {
        //Skapa Genre formulär
        $formId = uniqid($genre['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."book/saveeditgenre",
        "genreId"=>$genre['Id']);

        $text = "<h1>Editera ".$genre['Name']."</h1>";
        $text .= "<form method='post'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtBookGenre'>Genre</label></td> <td><input type='text' id='txtBookGenre' 
        name='BookGenre' pattern='.{3,}' placeholder='Genre namn' value='".$genre['Name']."' /></td> </tr>";
        $text .= "<tr> <td><label for='txtGenreDescription'>Beskrivning</label></td> 
                    <td><textarea id='txtGenreDescription' name='GenreDescription' rows='5' cols='30' 
                    pattern='.{5,}' placeholder='Minst 5 tecken'>".$genre['Description']."</textarea></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='btnEditGenre' value='Edit' />
        <input type='hidden' name='formname' value='".$formId."' /></td></tr>";
        $text .= "</table></form>";
    }
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
    $text .= "<b>Genre:</b><a href='".prefix."showgenre?id=".$book['GenreId']."'>".$book['GenreName']."</a><br />";
    $text .= "<b>Utgivningsår:</b> ".$book['PublicationYear']."<br />";
    $text .= "<b>ISBN: </b>".$book['ISBN']."<br />";
    if ($book['Rating'] != "n/a")
    {
        $text .= "<b>Betyg: </b>".$book['Rating']."/5<br /></p>";
    }
    else
    {
        $text .= "<b>Betyg: </b>".$book['Rating']."<br /></p>";
    }
    $text .= "<h3>Beskrivning</h3>";
    $text .= "<p>".$book['Description']."</p>";
    if (str_contains($role,"User") || str_contains($role,"Admin"))
    {
        $text .= "<form method='post' action='".prefix."review/newreview' >
        <button type='submit' name='bookId'value='".$book['Id']."'>Skriv recension</button></form>";
    }

    return $text;
}
function EditBook($book,$role)
{
    $text = "";
    if (str_contains($role,"Admin"))
    {
        
    }
    return $text;
}
function ShowAllBooks($arr,$role)
{
    $text = "<h1>Visa alla böcker</h1>";
    if ($role == "Admin")
    {
        $text .= "<table id='myTable' class='table'><thead><tr> <th>Titel <button onclick='sortTable(0)'>sort</button></th> <th>År <button onclick='sortTable(1)'>sort</button></th> <th>Beskrivning</th> <th>Genre <button onclick='sortTable(3)'>sort</button></th> <th>Författare <button onclick='sortTable(4)'>sort</button></th> <th>Visa</th>
        <th>Edit</th><th>Radera</th></tr></thead>";
    }
    else
    {
        $text .= "<table id='myTable' class='table'><thead><tr> <th>Titel <button onclick='sortTable(0)'>sort</button></th> <th>År <button onclick='sortTable(1)'>sort</button></th> <th>Beskrivning</th> <th>Genre <button onclick='sortTable(3)'>sort</button></th> <th>Författare <button onclick='sortTable(4)'>sort</button></th> <th>Visa</th>
        </tr></thead>";
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
        $text.= "</tr></tbody>";
    }
    if ($role == "Admin")
    {
        $text.= "</table><form method='post' action='".prefix."books/createbook'><button type='submit'>Skapa ny bok</button></form>";
    }
    $text.= "<script src='".prefix."js/sortMe.js'></script>";
    return $text;
}

function ShowGenre($dataArr)
{
    $text = "<h1>Visa Genre</h1>";
    $text .= "<h2>".$dataArr['Genre']['Name']."</h2>";
    $text .= "<p><b>Beskrivning</b> ".$dataArr['Genre']['Description']."<br />";
    $text .= "<b>Skapad</b> " .$dataArr['Genre']['Created']."<br />";

    $text .= "<h2>Böcker som finns i denna genre</h2>";
    foreach ($dataArr['Books'] as $key => $row) {
        $text .= "<a href='".prefix."showbook?id=".$row['Id']."'>".$row['Title']."(".$row['PublicationYear'].")</a><br />";
    }
    return $text;
}

function ShowAllGenre($arr,$role)
{
    $text = "<h1>Visa alla genre</h1>";
    if (str_contains($role,"Admin"))
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
        $text.= "<td><form method='post' action='".prefix."showgenre?id=".$row['Id']."'><button type='submit'>Visa</input>
        </form></td>";
        if (str_contains($role,"Admin"))
        {
            $text.= "<td><form method='post' action='".prefix."books/editgenre'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."books/deletegenre'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if (str_contains($role,"Admin"))
    {
        $text.= "</table><form method='post' action='".prefix."books/creategenre'><button type='submit'>Skapa ny genre</button></form>";
    }
    return $text;
}

function ShowSearch()
{
    
}

?>