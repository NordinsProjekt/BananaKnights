<?php



function ShowAllAuthors($arr,$role)
{
    $text = "";
    $text .= "<h1 id='DarkModeH1' class='display-4' style='text-align:center; padding: 20px 0 20px 0'>Visa alla författare</h1>";

    if ($role == "Admin")
    {
        $text.= "<div style='text-align: center; padding-bottom: 50px;'>";
        $text.= "</table><form method='post' action='".prefix."author/newauthor'><button class='btn btn-outline-primary' type='submit'>Skapa ny författare</button></form>";
        $text.= "</div>";

        $text .= "<table class='table table-bordered table-dark table-hover'><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table class='table table-bordered table-dark table-hover'><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Visa</th></tr>";
    }
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td><form method='post' action='".prefix."authors/show'><button class='btn btn-outline-primary' type='submit' name='id' 
        value='".$row['Id']."'>Visa</input></form></td>";
        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."authors/edit'><button class='btn btn-outline-warning' type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."authors/delete'><button class='btn btn-outline-danger' type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    return $text;
}


function ShowAuthor($dataArr,$role)
{
    $text = "";
    $text .= "<div style='width: 100%; display: flex; justify-content: center; padding: 50px 0 50px 0;'>";
    $text .= "<div style='width: 60rem;'>";
    $text .= "<img style='float:left;' class='authorImage' src='".$dataArr['Author']['ImageLink']."' />";
    $text .= "<div class='card mb-3'>";
    $text .= "<div class='card-body'>";
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
        <input type='hidden' name='formname' value='".$formId."' /'><button class='btn btn-outline-warning' type='submit'>Anmäl</button></form>";
    }
    if ($role == "Admin" && $dataArr['Author']['Flagged'] == 1)
    {
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $formId = uniqid($dataArr['Author']['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/unflag",
        "authorId"=>$dataArr['Author']['Id']);
        $text .= "<form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."' /'><button class='btn btn-outline-success' type='submit'>Återställ</button></form>";
    }
    $text .= "<h4>Böcker författaren har skrivit</h4>";
    foreach ($dataArr['Books'] as $key => $row) {
        $text .= "<a href='".prefix."showbook?id=".$row['Id']."'>".$row['Title']."(".$row['PublicationYear'].")</a><br />";
    }

    $text .= "</div>";
    $text .= "</div>";
    $text .="</div>";
    $text .="</div>";
    return $text;
}


function AddNewAuthor()
{
    $text = "";
    $text .= "<div class='container' style='text-align: center; padding-top: 50px;'>";
    $text .= "<h1 class='display-4'>Skapa ny författare</h1>";
    $text .= "<form style='padding: 0 0 0 420px;' method='post' action='".prefix."author/addauthor' enctype='multipart/form-data'>";
    $text .= "<table>";
    $text .= "<tr><td><label for='firstname'>Firstname</label></td> <td><input type='text' class='form-control' id='firstname' name='Fname' required /></td> </tr>";  
    $text .= "<tr><td><label for='lastname'>Lastname</label></td> <td><input type='text' class='form-control' id='lastname' name='Lname' required /></td> </tr>"; 
    $text .= "<tr><td><label for='country'>Country</label></td> <td><input type='text' class='form-control' id='country' name='Country' required /></td> </tr>"; 
    $text .= "<tr><td><label for='born'>Born</label></td> <td><input type='date' class='form-control' id='born' name='Born' required/></td> </tr>"; 
    $text .= "<tr><td><label for='death'>Death</label></td> <td><input type='date' class='form-control' id='death' name='Death' /></td> </tr>";
    $text .= "<tr> <td><label for='txtAuthorPicture'>Bild</label></td> <td><input type='file' class='form-control' id='txtAuthorPicture' name='AuthorPicture' /></td> </tr>";

    // behöver lägga till bild table i db
    //$text .= "<tr> <td><label for='authorpic'>Bild</label></td> <td><input type='file' id='authorpic' name='AuthorPic' /></td> </tr>";
    $text .= "<tr> <td></td> <td><input type='submit' class='btn btn-outline-primary' name='addauthor' value='Spara' /></td> </tr>";
    $text .= "</table></form>";
    $text .= "</div>";
    return $text;
}

function EditAuthor($author,$role)
{
    $text = "";
    $text .= "<div class='container' style='text-align: center;'>";
    if (str_contains($role,"Admin"))
    {
        $formId = uniqid($author['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/saveeditauthor",
        "authorId"=>$author['Id']);

        $text = "<h1 class='display-4' style='text-align: center; padding-top: 50px;'>Editera ".$author['Firstname']." " .$author['Lastname']."</h1>";
        $text .= "<form method='post' style='padding: 0 0 0 600px;'>";
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
        $text .= "<tr><td><label for='death'>Bildsökväg</label></td> 
        <td><input type='text' id='imagepath' class='form-control' name='ImagePath' value='".$author['ImagePath']."' required /></td> </tr>";
    
        // behöver lägga till bild table i db
        //$text .= "<tr> <td><label for='authorpic'>Bild</label></td> <td><input type='file' id='authorpic' name='AuthorPic' /></td> </tr>";
        $text .= "<tr> <td></td> <td><input type='hidden' name='formname' value='".$formId."' />
            <input type='submit' name='saveauthor' class='btn btn-outline-primary' value='Spara' /></td> </tr>";
        $text .= "</table></form>";
        $text .= "</div>";
    }
    return $text;
}





?>