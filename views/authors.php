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






?>