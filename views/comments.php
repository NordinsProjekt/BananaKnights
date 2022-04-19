<?php



function CreateNewComment($arr)
{
        $text = "<form method='post' action='".prefix."review/addcomment'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtComment'>Kommentar</label></td> <td><input type='text' id='txtComment' 
                name='Comment' placeholder='Kommentar'/></td> </tr>";
        $text .= "<td><button type='submit' name='id' value='".$arr['Id']."'>Submit</input></td>";
        $text .= "</table></form>";
        return $text;
}


function ShowAllComments($arr,$role)
{
    $text = "";
    if ($role == "Admin")
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th> 
                <th>Edit</th> <th>Radera</th></tr>";
    }
    elseif($role == "Moderator")
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th> 
                <th>Flagga</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th></tr>";
    }
    
    foreach ($arr as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td>".$row['Comment']."</td>";
        $text.= "</form></td>";

        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."comments/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."comments/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        elseif($role == "Moderator")
        {
            $text.= "<td><form method='post' action='".prefix."comments/flag'><button type='submit' name='id' value='".$row['Id']."'>Flagga</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    return $text;
}





















?>