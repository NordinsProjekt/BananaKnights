<?php
function ShowUserAdmin($formData)
{
    $text = "<h1>Enskild användare</h1>";
    $text .= "<h2>".$formData['User']['UserName']."</h2>";
    $text .= "<table><tr> <th></th> <th></th> </tr>";
    $text .= "<tr> <td>Email</td> <td>".$formData['User']['Email']."</td> </tr>";
    $text .= "<tr><td>Användarroller</td><td></td></tr>";
    foreach ($formData['UserRoles'] as $key => $row) {
        $text .= "<tr><td>".$row['Name']."</td><td><form method='post' action='admin/removerolefromuser'>
        <button type='submit' name='roleid' value='".$row['Id']."'>Radera</button></form></td></tr>";
    }
    $text .= "<tr><td></td><td></td></tr>";
    foreach ($formData['AllRoles'] as $key => $row) {
        //Listar bara de rollerna som användaren inte har
        //Inte vacker men löser problemet :) Om sökningen returnerar ett index så ignorerar vi den raden
        if(is_numeric(array_search($row['Name'],array_column($formData['UserRoles'],'Name'))))
        { }
        else
        {
            $text .= "<tr><td>".$row['Name']."</td><td><form method='post' action='admin/addrolestouser'>
            <button type='submit' name='roleid' value='".$row['Id']."'>Lägg till</button></form></td></tr>";
        }
        

    }
    $text .= "</table>";
    
    $text .= "";
    return $text;
}

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
        $text.= "<td><form method='post' action='".prefix."admin/showuserform'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
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