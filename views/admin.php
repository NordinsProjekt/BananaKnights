<?php

function AdminIndex($formData)
{
    $text = "<h1>Adminpanel</h1>";
    $text .= "<h2>Hantera Användare</h2>";
    $text .= "<a href='".prefix."admin/showall'>Visa alla användare</a>";
    $text .= "<h2>Hantera Roller</h2>";
    $text .= "<h2>Statistik</h2>";
    $text .= "<a href='".prefix."showstats'>Visa statistik</a>";
    $text .= "<h2>Avstängda konton</h2>";
    $text .= GenerateTableWithUsers($formData['BannedUsers']);
    $text .= "<h2>Raderade Böcker</h2>";
    $text .= GenerateTableDeletedBooks($formData['DeletedBooks']);
    $text .= "<h2>Raderade Genre</h2>";
    $text .= GenerateTableWithGenre($formData['BannedGenre']);
    $text .= "<h2>Raderade Författare</h2>";
    $text .= GenerateTableDeletedAuthors($formData['DeletedAuthors']);
    $text .= "<h2>Raderade Recensioner</h2>";
    $text .= GenereateTableDeletedReviews($formData['DeletedReviews']);
    return $text;
}

function GenerateTableWithGenre($genre)
{
    $text = "";
    if (empty($genre))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Namn</th> <th>Beskrivning</th> <th>Skapad</th> <th>Återställ</th></tr>";
    foreach ($genre as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Name']."</td>";
        $text.= "<td>".$row['Description']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."books/revivegenre'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</input></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

function GenereateTableDeletedReviews($reviews)
{
    $text = "";
    if (empty($reviews))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Titel</th> <th>Bok titel</th> <th>Betyg</th> <th>Skriven av</th> <th>Skapad</th> <th>Återställ</th></tr>";
    foreach ($reviews as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['ReviewTitle']."</td>";
        $text.= "<td>".$row['BookTitle']."</td>";
        $text.= "<td>".$row['Rating']."</td>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."review/undelete'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</input></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}
function GenerateTableDeletedBooks($books)
{
    $text = "";
    if (empty($books))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Titel</th> <th>Författare</th> <th>Genre</th> <th>Utgivningsår</th> <th>Återställ</th></tr>";
    foreach ($books as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['AuthorName']."</td>";
        $text.= "<td>".$row['GenreName']."</td>";
        $text.= "<td>".$row['PublicationYear']."</td>";
        $text.= "<td><form method='post' action='".prefix."books/undelete'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</input></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}
function GenerateTableDeletedAuthors($authors)
{
    $text = "";
    $text = "";
    if (empty($authors))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Country</th> <th>Skapad</th> <th>Återställ</th></tr>";
    foreach ($authors as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td>".$row['Country']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."authors/undelete'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</input></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}



function StatsPanel($statsData)
{
    $text = "";
    $text .= "<h1>Stats för CoolBooks</h1>";
    $text .= "<p><b>Antal böcker i databasen:</b> ".$statsData['Books']['NumberofBooks']."<br />";
    $text .= "<b>Antal författare i databasen:</b> ".$statsData['Authors']['NumberofAuthors']."<br />";
    $text .= "<b>Antal genre i databasen:</b> ".$statsData['Genre']['NumberofGenre']."<br />";
    $text .= "<b>Antal användare i databasen:</b> ".$statsData['Users']['NumberofUsers']."<br />";
    $text .= "<b>Antal recensioner i databasen:</b> ".$statsData['Reviews']['NumberofReviews']."<br />";
    $text .= "<b>Antal kommentarer i databasen:</b> ".$statsData['Comments']['NumberofComments']."<br />";
    $text .= "<b>Top spammer:</b> ".$statsData['Spammer']['UserName']." (".$statsData['Spammer']['NumberofComments'].")</p>";
    return $text;
}

function GenerateTableWithUsers($users)
{
    $text = "";
    if (empty($users))
    {
        return $text;
    }
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
        $text .= "<table><tr> <th>Användarnamn</th> <th>Email</th> <th>Roller</th> <th>Visa</th>
        <th>Edit</th><th>Radera</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Användarnamn</th> <th>Email</th> <th>Roller</th>Ändra<th></th></tr>";
    }
    foreach ($users as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Email']."</td>";
        $text.= "<td>".$row['Roles']."</td>";
        $text.= "<td><form method='post' action='".prefix."admin/showuserform'><button type='submit' name='id' value='".$row['Id']."'>Ändra roller</input>
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

function SiteSettings($role)
{
    
}
?>