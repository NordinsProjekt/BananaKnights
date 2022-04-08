<?php



function ShowAllAuthors($arr)
{
    $text = "<h1>Visa alla författare</h1>";
    $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th></tr>";
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td><form><input type='submit' name='visaAuthor' value='Visa' />
        <input type='hidden' name='author' value='" .$row['Id'] . " />'</form></td>";
        $text.= "</tr>";
    }
    return $text;
}


function ShowAuthor($author)
{
    $text = "<h1>Visa Författare</h1>";
    foreach ($author as $key => $value) {
        $text .= "<p>".$key.": ".$value."</p>";
    }
    return $text;
}


function AddNewAuthor()
{
        //Skapa Bok formuläret
        $text = "<h1>Skapa ny författare</h1>";
        $text .= "<form method='post' action='addauthor'>";
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