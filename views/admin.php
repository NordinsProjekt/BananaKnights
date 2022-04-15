<?php
function ShowAllUsersAdmin($users,$role)
{
    $text = "<h1>Visa alla användare</h1>";
    if ($role == "Admin")
    {
        $text .= "<table><tr> <th>Användarnamn</th> <th>Email</th> <th>Roller</th> <th>Visa</th></tr>
        <th>Edit</th><th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Användarnamn</th> <th>Email</th> <th>Roller</th> <th>Visa</th></tr>";
    }
    foreach ($users as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Email']."</td>";
        $text.= "<td>".$row['Roles']."</td>";
        $text.= "<td><form method='post' action='".prefix."user/show'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
        if ($role == "Admin")
        {
            $text.= "<td><form method='post' action='".prefix."user/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."user/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
        }
        $text.= "</tr>";
    }
    if ($role == "Admin")
    {
        $text.= "</table><form method='post' action='".prefix."admin/createuser'><button type='submit'>Skapa ny användare</button></form>";
    }
    return $text;
}
?>