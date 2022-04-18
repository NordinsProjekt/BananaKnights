<?php

function AdminIndex($formData)
{
    $text = "<h1>Adminpanel</h1>";
    $text .= "<h2>Hantera Användare</h2>";
    $text .= "<a href='".prefix."admin/showall'>Visa alla användare</a>";
    $text .= "<h2>Hantera Roller</h2>";
    $text .= "<h2>Hantera Kommentarer</h2>";
    $text .= "<h2>Avstängda konton</h2>";
    $text .= GenerateTableWithUsers($formData['BannedUsers']);
    $text .= "<h2>Flaggade författare</h2>";
    $text .= GenerateTableWithAuthors($formData['BannedAuthors']);
    
    return $text;
}
function GenerateTableWithAuthors($authors)
{
    $text = "";
        $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
    foreach ($authors as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td><form method='post' action='".prefix."authors/show'><button type='submit' name='id' 
        value='".$row['Id']."'>Visa</input></form></td>";
            $text.= "<td><form method='post' action='".prefix."authors/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."authors/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";

        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

function GenerateTableWithUsers($users)
{
    $text = "";
    $text .= "<table><tr> <th>Användarnamn</th> <th>Email</th> <th>Roller</th> <th>Visa</th>
    <th>Edit</th><th>Radera</th> <th>Återställ lösenord</th></tr>";
    foreach ($users as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Email']."</td>";
        $text.= "<td>".$row['Roles']."</td>";
        $text.= "<td><form method='post' action='".prefix."admin/showuserform'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        </form></td>";
            $text.= "<td><form method='post' action='".prefix."user/edit'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."user/delete'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."admin/resetpassword'><button type='submit' name='id' value='".$row['Id']."'>Återställ lösenord</input>
            </form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

function ShowUserAdmin($formData)
{
    $text = "<h1>Enskild användare</h1>";
    if ($formData['User']['LockoutEnabled'] == 0)
    {
        $text .= "<h2>".$formData['User']['UserName']."</h2>";
    }
    else
    {
        $text .= "<h2>".$formData['User']['UserName']." (AVSTÄNGD)</h2>";
    }
    
    $text .= "<table><tr> <th></th> <th></th> </tr>";
    $text .= "<tr> <td>Email</td> <td>".$formData['User']['Email']."</td> </tr>";
    $text .= "<tr><td>Användarroller</td><td></td></tr>";
    foreach ($formData['UserRoles'] as $key => $row) {
        $formId = uniqid($row['Id'],true);
        $text .= "<tr><td>".$row['Name']."</td><td><form method='post' action='lol'>
        <input type='hidden' name='formname' value='".$formId."'>
        <button type='submit'>Radera</button></form></td></tr>";
        //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."admin/removerolefromuser",
        "userId"=>$formData['User']['Id'], "roleId"=>$row['Id']);
    }


    $text .= "<tr><td></td><td></td></tr>";
    foreach ($formData['AllRoles'] as $key => $row) {
        //Listar bara de rollerna som användaren inte har
        //Inte vacker men löser problemet :) Om sökningen returnerar ett index så ignorerar vi den raden
        if(is_numeric(array_search($row['Name'],array_column($formData['UserRoles'],'Name'))))
        { }
        else
        {
            $formId = uniqid($row['Id'],true);
            $text .= "<tr><td>".$row['Name']."</td><td><form method='post' action='lol'>
            <input type='hidden' name='formname' value='".$formId."'>
            <button type='submit'>Lägg till</button></form></td></tr>";
            //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."admin/addrolestouser",
            "userId"=>$formData['User']['Id'], "roleId"=>$row['Id']);
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