<?php

function CreateNewBook($arrGenre,$arrAuthor)
{
    //Skapa Bok formuläret
    $text = "";
    $text .= "<div class='container' style='text-align: center;'>";
    $text .= "<h1 class='display-4' style='padding: 40px 0 30px 0;'>Skapa ny bok</h1>";
    $text .= "<form method='post' action='".prefix."books/savebook' enctype='multipart/form-data' style='padding: 0 0 0 420px;'>";
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
    $text .= "<tr> <td></td> <td><input type='submit' class='btn btn-outline-primary' name='btnSaveBook' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
    $text .= "</div>";
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
    $text = "";
    $text .= "<div style='width: 100%; display: flex; justify-content: center; padding: 50px 0 50px 0;'>";
    $text .= "<div style='width: 60rem;'>";
    $text .= "<img width='250px' height='400px' src='".$imageLink."' style='float:left;'/>";
    $text .= "<div class='card mb-3'>";
    $text .= "<div class='card-body'>";
    $text .=   "<h1 class='card-title'>".$book['Title']."</h1>";
    $text .= "<p><b>Författare:</b> <a href='".prefix."showauthor?id=".$book['AuthorId']."'>".$book['AuthorName']."</a><br>";
    $text .= "<b>Genre:</b><a href='".prefix."showgenre?id=".$book['GenreId']."'>".$book['GenreName']."</a><br>";
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
    $text .= "<h4>Beskrivning:</h4>";
    $text .= "<p>".$book['Description']."</p>";
    $text .=   "<p class='card-text'><small class='text-muted'><b>Tillagd:</b> ".$book['Created']."</small></p>";
    $text .= "<div style='width: 100%; display: flex; justify-content: flex-start;'>";
    if ($role != "")
    {
        $text .= "<form method='post' action='".prefix."books/recommend' style='padding-top: 24px;'>";
        if ($book['Recommend'])
        {
            $text .= "<button style='margin-right: 10px;' class='btn btn-success' type='submit' name='id' value='".$book['Id']."'>Ta bort läslistan</button></form>";
        }
        else
        {
            $text .= "<button style='margin-right: 10px;' class='btn btn-outline-success' type='submit' name='id' value='".$book['Id']."'>Lägg till läslistan</button></form>";
        }
    }
    if (str_contains($role,"User") || str_contains($role,"Admin"))
    {
        $text .= "<form style='margin-right: 10px;' method='post' action='".prefix."review/newreview' >
        <button class='btn btn-outline-primary' type='submit' name='bookId'value='".$book['Id']."'>Skriv recension</button></form>";
        $text .= "<form style='margin-right: 10px;' method='post' action='".prefix."quiz/create' >
        <button class='btn btn-outline-primary' type='submit' name='bookId' value='".$book['Id']."'>Skapa ett quiz</button></form>";

    }
    $text .= "</div>";

    $text .="</div>";
    $text .="</div>";
    $text .="</div>";
    $text .="</div>";
    $text .="<hr>";
    $text .= "</div>";
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
        
        $text = "";
        $text .= "<div class='container' style='text-align: center;'>";
        $text .= "<h1 class='display-4' style='padding: 40px 0 30px 0;'>Editera ".$formData['Book']['Title']."</h1>";
        $text .= "<form method='post' style='padding: 0 0 0 420px;'>";
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
        $text .= "<tr> <td></td> <td><input type='submit' name='btnSaveEditBook' class='btn btn-outline-primary' value='Spara' />
            <input type='hidden' name='formname' value='".$formId."' /></td> </tr>";
        $text .= "</table></form>";
        $text .= "</div>";
        //Skapa add picture/remove picture formulär
    }
    return $text;
}
function ShowAllBooks($arr,$user)
{  
    $text = "";
    $text .= "<div style='padding-top: 40px;'>";
    $text .= "<h1 class='display-4' style='text-align: center;'>Visa alla böcker</h1>";
    $text .= "</div>";
    

    $text .= "<div class='links-unordered' style='text-align:center; padding-bottom: 60px;'>";
    $text .= "<a class='toggle-button' href='#' style='text-decoration:none;'>Avancerad sökning</a>";
    $text .= "<ul style='display:none; padding: 15px 0 0 0; list-style: none;'>";
    $text .= "<form method='post' action='".prefix."books/searchgenre' style='padding-bottom: 10px;'><li><small>Genre</small><br><input style='height: 38px;' type='text' name='genre' placeholder='sök...'><button style='vertical-align: baseline;' class='btn btn-outline-primary' type='submit'>Filter</button></li></form>";
    $text .= "<form method='post' action='".prefix."books/searchauthor'><li><small>Författare</small><br><input style='height: 38px;' type='text' name='author' placeholder='sök...'><button style='vertical-align: baseline;' class='btn btn-outline-primary' type='submit'>Filter</button></li></form>";
    $text .= "</ul>";
    $text .= "</div>";
    if (isset($_SESSION['is_logged_in']))
    {
        $text.= "<div style='text-align: center;'>";
        $text.= "<small>Saknar du en bok?</small>";
        $text.= "<form method='post' action='".prefix."books/createbook'><button class='btn btn-outline-primary' style='margin: 5px 0 20px 0;' type='submit'>Skapa ny bok</button></form>";
        $text.= "</div>";
    }

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
        $text.= "<td><form method='post' action='".prefix."books/show'><button class='btn btn-outline-primary' type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
        if (str_contains($user['Roles'],"Admin"))
        {
            $text.= "<td><form method='post' action='".prefix."books/edit'><button class='btn btn-outline-warning' type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."books/delete'><button class='btn btn-outline-danger' type='submit' name='id' value='".$row['Id']."'>Radera</input>
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