<?php



function ShowAllAuthors($arr,$role)
{
    $text = "<h1>Visa alla författare</h1>";
    if ($role == "Admin")
    {
        $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Visa</th></tr>";
    }
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td><form method='post' action='".prefix."authors/show'><button type='submit' name='id' 
        value='".$row['Id']."'>Visa</input></form></td>";
        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."authors/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."authors/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if ($role == "Admin")
    {
        $text.= "</table><form method='post' action='".prefix."author/newauthor'><button type='submit'>Skapa ny författare</button></form>";
    }
    return $text;
}


function ShowAuthor($author,$role)
{
    $text = "<h1>Visa Författare</h1>";
    $text .= "<h2>".$author['Firstname']." " . $author['Lastname'] ."</h2>";
    $text .= "<p><b>Land:</b> ".$author['Country']."<br />";
    $text .= "<b>Född:</b> " .$author['Born']."<br />";

    if ($author['Death'] != "0000-00-00")
    { $text .= "<b>Död:</b> ".$author['Death']."<br />"; }

    if ($role == "Moderator" && $author['Flagged'] == 0)
    {
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $formId = uniqid($author['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/flagged",
        "authorId"=>$author['Id']);
        $text .= "<form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Anmäl</button></form>";
    }
    if ($role == "Admin" && $author['Flagged'] == 1)
    {
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $formId = uniqid($author['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/unflag",
        "authorId"=>$author['Id']);
        $text .= "<form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Återställ</button></form>";
    }
    $text .= "<h2>Böcker författaren har skrivit</h2>";

    return $text;
}


function AddNewAuthor()
{
        $text = "<h1>Skapa ny författare</h1>";
        $text .= "<form method='post' action='".prefix."author/addauthor'>";
        $text .= "<table>";
        $text .= "<tr><td><label for='firstname'>Firstname</label></td> <td><input type='text' id='firstname' name='Fname' /></td> </tr>";  
        $text .= "<tr><td><label for='lastname'>Lastname</label></td> <td><input type='text' id='lastname' name='Lname' /></td> </tr>"; 
        $text .= "<tr><td><label for='country'>Country</label></td> <td><input type='text' id='country' name='Country' /></td> </tr>"; 
        $text .= "<tr><td><label for='born'>Born</label></td> <td><input type='date' id='born' name='Born' /></td> </tr>"; 
        $text .= "<tr><td><label for='death'>Death</label></td> <td><input type='date' id='death' name='Death' /></td> </tr>"; 

        // behöver lägga till bild table i db
        //$text .= "<tr> <td><label for='authorpic'>Bild</label></td> <td><input type='file' id='authorpic' name='AuthorPic' /></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='submit' name='addauthor' value='add' /></td> </tr>";
        $text .= "</table></form>";
        return $text;
}






?>