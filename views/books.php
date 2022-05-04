<?php

function CreateNewBook($arrGenre,$arrAuthor)
{
    //Skapa Bok formuläret
    $text = "<h1>Skapa ny bok</h1>";
    $text .= "<form method='post' action='".prefix."books/savebook' enctype='multipart/form-data'>";
    $text .= "<table><tr><th></th><th></th></tr>";
    $text .= "<tr> <td><label for='txtBookTitle'>Titel</label></td> <td><input class='form-control' type='text' id='txtBookTitle' name='BookTitle' placeholder='Bokens titel' required /></td> </tr>";
    $text .= "<tr> <td><label for='selAuthor'>Författare</label></td> <td><select name='BookAuthor' id='selAuthor' class='form-select' >";
    foreach ($arrAuthor as $key => $value) {
        $text.= "<option value='".$value['Id']."'>".$value['Firstname']." ".$value['Lastname']."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr> <td><label for='selGenre'>Genre</label></td> <td><select name='BookGenre' id='selGenre' class='form-select' >";
    foreach ($arrGenre as $key => $value) {
        $text.= "<option value='" . $value['Id'] . "'>".$value['Name']."</option>";
    }    
    $text .= "</select></td> </tr>";
    $text .= "<tr><td><label for='pubYear'>Utgivningsdatum</label></td> <td><input type='text' class='form-control' size='4' 
    id='pubYear' name='BookYear' pattern ='[0-9]{0,4}' placeholder='ex 1986'/></td>";
    $text .= "<tr> <td><label for='txtBookDescription'>Beskrivning</label></td> 
              <td><textarea id='txtBookDescription' name='BookDescription' class='form-control' rows='5' cols='30' 
              placeholder='Beskrivande text' required></textarea></td> </tr>";
    $text .= "<tr> <td><label for='txtBookISBN'>ISBN</label></td> <td><input type='text' id='txtBookISBN' name='BookISBN'
     placeholder='ISBN nummer' class='form-control' required /></td> </tr>";
    $text .= "<tr> <td><label for='txtBookPicture'>Bild</label></td> <td><input type='file' class='form-control' id='txtBookPicture' name='BookPicture' /></td> </tr>";
    $text .= "<tr> <td></td> <td><input type='submit' class='btn btn-primary' name='btnSaveBook' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
    return $text;
}

function CreateNewGenre()
{
        //Skapa Genre formulär
        $text = "<h1>Skapa ny genre</h1>";
        $text .= "<form method='post' action='".prefix."books/savegenre'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtBookGenre'>Genre</label></td> <td><input type='text' class='form-control' id='txtBookGenre' 
        name='BookGenre' placeholder='Genre namn' required /></td> </tr>";
        $text .= "<tr> <td><label for='txtGenreDescription'>Beskrivning</label></td> 
                  <td><textarea id='txtGenreDescription' class='form-control' name='GenreDescription' rows='5' cols='30' 
                  pattern='.{5,}' placeholder='Beskrivning' required></textarea></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' class='btn btn-primary' name='btnSaveGenre' value='Spara' /></td> </tr>";
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
        name='BookGenre' placeholder='Genre namn' class='form-control' value='".$genre['Name']."' required /></td> </tr>";
        $text .= "<tr> <td><label for='txtGenreDescription'>Beskrivning</label></td> 
                    <td><textarea id='txtGenreDescription' class='form-control' name='GenreDescription' rows='5' cols='30' 
                 placeholder='Beskrivning' required >".$genre['Description']."</textarea></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='btnEditGenre' class='btn btn-primary' value='Edit' />
        <input type='hidden' name='formname' value='".$formId."' /></td></tr>";
        $text .= "</table></form>";
    }
    return $text;
}

function ShowBook($book,$imageLink,$role)
{
    $text = "<h1>Visa enskild bok</h1>";
    $text .= "<h2>".$book['Title']."</h2>";
    $text .= "<img class='bookImage' src='".$imageLink."' />";
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
    if ($role != "")
    {
        $text .= "<form method='post' action='".prefix."books/recommend'>";
        if ($book['Recommend'])
        {
            $text .= "<button type='submit' name='id' value='".$book['Id']."' style='background-color:green'>Lägg till läslistan</button></form>";
        }
        else
        {
            $text .= "<button type='submit' name='id' value='".$book['Id']."'>Lägg till läslistan</button></form>";
        }
    }
    if (str_contains($role,"User") || str_contains($role,"Admin"))
    {
        $text .= "<form method='post' action='".prefix."review/newreview' >
        <button type='submit' name='bookId'value='".$book['Id']."'>Skriv recension</button></form>";
        $text .= "<form method='post' action='".prefix."quiz/create' >
        <button type='submit' name='bookId' value='".$book['Id']."'>Skapa ett quiz</button></form>";

    }

    return $text;
}
function EditBook($formData,$role)
{
    $text = "";
    if (str_contains($role,"Admin"))
    {
        //Skapa Bok formuläret
        $formId = uniqid($formData['Book']['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."book/saveeditbook",
        "bookId"=>$formData['Book']['Id']);
        $text = "<h1>Editera ".$formData['Book']['Title']."</h1>";
        $text .= "<form method='post'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtBookTitle'>Titel</label></td> 
        <td><input type='text' id='txtBookTitle' name='BookTitle' class='form-control' value='".$formData['Book']['Title']."' required /></td> </tr>";
        $text .= "<tr> <td><label for='selAuthor'>Författare</label></td> <td><select name='BookAuthor' class='form-select' id='selAuthor'>";
        foreach ($formData['Authors'] as $key => $value) {
            if ($value['Id'] == $formData['Book']['AuthorId'])
            {
                $text.= "<option value='".$value['Id']."'selected>".$value['Firstname']." ".$value['Lastname']."</option>";
            }
            else
            {
                $text.= "<option value='".$value['Id']."'>".$value['Firstname']." ".$value['Lastname']."</option>";
            }
        }    
        $text .= "</select></td> </tr>";
        $text .= "<tr> <td><label for='selGenre'>Genre</label></td> <td><select name='BookGenre' class='form-select' id='selGenre'>";
        foreach ($formData['Genres'] as $key => $value) {
            if ($value['Id'] == $formData['Book']['AuthorId'])
            {
                $text.= "<option value='" . $value['Id'] . "' selected>".$value['Name']."</option>";
            }
            else
            {
                $text.= "<option value='" . $value['Id'] . "'>".$value['Name']."</option>";
            }
        }    
        $text .= "</select></td> </tr>";
        $text .= "<tr><td><label for='pubYear'>Utgivningsdatum</label></td> <td><input type='text' size='4' 
        id='pubYear' name='BookYear' class='form-control' pattern ='[0-9]{0,4}' value='".$formData['Book']['PublicationYear']."' /></td>";
        $text .= "<tr> <td><label for='txtBookDescription'>Beskrivning</label></td> 
                <td><textarea id='txtBookDescription' class='form-control' name='BookDescription' rows='5' cols='30' 
                placeholder='Beskrivande text' required'>".$formData['Book']['Description']."</textarea></td> </tr>";
        $text .= "<tr> <td><label for='txtBookISBN'>ISBN</label></td> <td><input type='text' id='txtBookISBN' class='form-control' name='BookISBN'
        placeholder='ISBN nummer' value='".$formData['Book']['ISBN']."' required/></td> </tr>";
        $text .= "<tr> <td><label for='txtBookPicture'>Bildpath</label></td> 
        <td><input type='text' id='txtBookPicture' class='form-control' name='BookPicturePath' value='".$formData['Book']['ImagePath']."' required /></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveEditBook' class='btn btn-primary' value='Spara' />
            <input type='hidden' name='formname' value='".$formId."' /></td> </tr>";
        $text .= "</table></form>";
        //Skapa add picture/remove picture formulär
    }
    return $text;
}
function ShowAllBooks($arr,$user)
{  
    $text = "<h1>Visa alla böcker</h1>";

    $text .= "<div class='links-unordered'>";
    $text .= "<a class='toggle-button' href='#'>Advanced Search</a>";
    $text .= "<ul style='display:none;'>";
    $text .= "<form method='post' action='".prefix."books/searchgenre'><li><label>Genre</label><br><input type='text' name='genre' placeholder='...'><button type='submit'>Filter</button></li></form>";
    $text .= "<form method='post' action='".prefix."books/searchauthor'><li><label>Författare</label><br><input type='text' name='author' placeholder='...'><button type='submit'>Filter</button></li></form>";
    $text .= "</ul>";
    $text .= "</div>";

    if (str_contains($user['Roles'],"Admin"))
    {
        $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr> <th onclick='sortTable(0)'>Titel</th> <th onclick='sortTable(1)'>År</th> <th>Beskrivning</th> <th onclick='sortTable(3)'>Genre</th> <th onclick='sortTable(4)'>Författare</th> <th>Visa</th>
        <th>Edit</th><th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr> <th onclick='sortTable(0)'>Titel</th> <th onclick='sortTable(1)'>År</th> <th>Beskrivning</th> <th onclick='sortTable(3)'>Genre</th> <th onclick='sortTable(4)'>Författare</th> <th>Visa</th>
        </tr>";
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
        if (str_contains($user['Roles'],"Admin"))
        {
            $text.= "<td><form method='post' action='".prefix."books/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."books/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if (str_contains($user['Roles'],"Admin") || str_contains($user['Roles'],"User"))
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
        $text .= "<table class='table table-bordered table-dark table-hover'><tr> <th>Namn</th> <th>Beskrivning</th> <th>Skapad</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table class='table table-bordered table-dark table-hover'><tr> <th>Namn</th> <th>Beskrivning</th> <th>Visa</th></tr>";
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