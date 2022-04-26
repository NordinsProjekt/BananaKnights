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


function ShowAuthor($dataArr,$role)
{
    $text = "<h1>Visa Författare</h1>";
    $text .= "<h2>".$dataArr['Author']['Firstname']." " . $dataArr['Author']['Lastname'] ."</h2>";
    $text .= "<p><b>Land:</b> ".$dataArr['Author']['Country']."<br />";
    $text .= "<b>Född:</b> " .$dataArr['Author']['Born']."<br />";

    if ($dataArr['Author']['Death'] != "0000-00-00")
    { $text .= "<b>Död:</b> ".$dataArr['Author']['Death']."<br />"; }

    if ($role == "Moderator" && $dataArr['Author']['Flagged'] == 0)
    {
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $formId = uniqid($dataArr['Author']['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/flagged",
        "authorId"=>$dataArr['Author']['Id']);
        $text .= "<form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Anmäl</button></form>";
    }
    if ($role == "Admin" && $dataArr['Author']['Flagged'] == 1)
    {
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $formId = uniqid($dataArr['Author']['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/unflag",
        "authorId"=>$dataArr['Author']['Id']);
        $text .= "<form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Återställ</button></form>";
    }
    $text .= "<h2>Böcker författaren har skrivit</h2>";
    foreach ($dataArr['Books'] as $key => $row) {
        $text .= "<a href='".prefix."showbook?id=".$row['Id']."'>".$row['Title']."(".$row['PublicationYear'].")</a><br />";
    }
    return $text;
}


function AddNewAuthor()
{
    $text = "<h1>Skapa ny författare</h1>";
    $text .= "<form method='post' action='".prefix."author/addauthor'>";
    $text .= "<table>";
    $text .= "<tr><td><label for='firstname'>Firstname</label></td> <td><input type='text' class='form-control' id='firstname' name='Fname' required /></td> </tr>";  
    $text .= "<tr><td><label for='lastname'>Lastname</label></td> <td><input type='text' class='form-control' id='lastname' name='Lname' required /></td> </tr>"; 
    $text .= "<tr><td><label for='country'>Country</label></td> <td><input type='text' class='form-control' id='country' name='Country' required /></td> </tr>"; 
    $text .= "<tr><td><label for='born'>Born</label></td> <td><input type='date' class='form-control' id='born' name='Born' required/></td> </tr>"; 
    $text .= "<tr><td><label for='death'>Death</label></td> <td><input type='date' class='form-control' id='death' name='Death' /></td> </tr>"; 

    // behöver lägga till bild table i db
    //$text .= "<tr> <td><label for='authorpic'>Bild</label></td> <td><input type='file' id='authorpic' name='AuthorPic' /></td> </tr>";
    $text .= "<tr> <td></td> <td><input type='submit' class='btn btn-primary' name='addauthor' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
    return $text;
}

function EditAuthor($author,$role)
{
    $text = "";
    if (str_contains($role,"Admin"))
    {
        $formId = uniqid($author['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/saveeditauthor",
        "authorId"=>$author['Id']);

        $text = "<h1>Editera ".$author['Firstname']." " .$author['Lastname']."</h1>";
        $text .= "<form method='post'>";
        $text .= "<table>";
        $text .= "<tr><td><label for='firstname'>Firstname</label></td> 
        <td><input type='text' id='firstname' class='form-control' name='Fname' value='".$author['Firstname']."' required /></td> </tr>";  
        $text .= "<tr><td><label for='lastname'>Lastname</label></td> 
        <td><input type='text' id='lastname' class='form-control' name='Lname' value='".$author['Lastname']."'  required /></td> </tr>"; 
        $text .= "<tr><td><label for='country'>Country</label></td> 
        <td><input type='text' id='country' class='form-control' name='Country' value='".$author['Country']."' required /></td> </tr>"; 
        $text .= "<tr><td><label for='born'>Born</label></td> 
        <td><input type='date' id='born' class='form-control' name='Born' value='".$author['Born']."' required /></td> </tr>"; 
        $text .= "<tr><td><label for='death'>Death</label></td> 
        <td><input type='date' id='death' class='form-control' name='Death' value='".$author['Death']."' /></td> </tr>";
    
        // behöver lägga till bild table i db
        //$text .= "<tr> <td><label for='authorpic'>Bild</label></td> <td><input type='file' id='authorpic' name='AuthorPic' /></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='hidden' name='formname' value='".$formId."' />
            <input type='submit' name='saveauthor' class='btn btn-primary' value='Spara' /></td> </tr>";
        $text .= "</table></form>";
    }
    return $text;
}





?>