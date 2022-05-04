<?php

function ModeratorIndex($formData,$role)
{   
    $text = "";
    if (str_contains($role,"Moderator"))
    {
        $text .= "<h1>Moderatorpanel</h1>";
        $text .= "<h2>Flaggade Kommentarer</h2>";
        $text .= GenerateTableFlaggedComments($formData['BannedComments'],$formData['BannedReply']);
        $text .= "<h2>Flaggade Författare</h2>";
        $text .= GenerateTableWithFlaggedAuthors($formData['BannedAuthors']);
        $text .= "<h2>Flaggade Recensioner</h2>";
        $text .= GenerateTableWithFlaggedReviews($formData['BannedReviews']);
        $text .= "<h2>Flaggade Quiz</h2>";
        $text .= GenerateTableWithFlaggedQuiz($formData['BannedQuiz']);
    }

    return $text;
}

function GenerateTableWithFlaggedQuiz($quiz)
{
    $text = "";
    if (empty($quiz))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Bok titel</th> <th>Quiz titel</th> <th>Användarnamn</th> <th>Återställ</th> </tr>";
    foreach ($quiz as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['BookTitle']."</td>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['UserName']."</td>";
        $formId = uniqid($row['Id'],true);

        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/unflag",
        "QuizId"=>$row['Id'],"BookId"=>$row['BookId']);
            $text.= "<td><form method='post'><button type='submit'>Återställ</input>
            <input type='hidden' name='formname' value='".$formId."' />
            </form></td>";

        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

function GenerateTableFlaggedComments($comments,$reply)
{
    $text = "";
    if (empty($comments) && empty($reply))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Användare</th> <th>Text</th> <th>Skapad</th> <th>Återställ</th></tr>";
    foreach ($comments as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Text']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."comment/unflag'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</button><input type='hidden' name='ReviewId' value='".$row['ReviewId']."' /></form></td>";
        $text.= "</tr>";
    }
    //Skriver ut alla svar för de är i en separat tabell
    foreach ($reply as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td>".$row['Text']."</td>";
        $text.= "<td>".$row['Created']."</td>";
        $text.= "<td><form method='post' action='".prefix."reply/unflag'><button type='submit' name='id' 
        value='".$row['Id']."'>Återställ</button><input type='hidden' name='ReviewId' value='".$row['ReviewId']."' /></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

function GenerateTableWithFlaggedReviews($reviews)
{
    $text = "";
    if (empty($reviews))
    {
        return $text;
    }


    $text .= "<table><tr> <th></th> <th>Boktitel</th> <th>Titel</th> <th>Användare</th> <th>Betyg</th ><th>Skapad</th> 
        <th>Återställ</th> </tr>";

    foreach ($reviews as $key => $row) {
        $formId = uniqid($row['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/unflag",
        "reviewId"=>$row['Id']);
        if (file_exists("img/books/". $row['BookImagePath']))
        {
            $pictures = scandir("img/books/". $row['BookImagePath']);
            if (empty($pictures[2]))
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            else
            {
                $imageLink = prefix."img/books/". $row['BookImagePath'] ."/". $pictures[2];
            }
        }
        else
        {
            $imageLink = prefix."img/books/noimage.jpg";
        }
        $text .= "<tr><td><img src='".$imageLink."' alt='book cover' height='100px' /></td>
        <td>".$row['BookTitle']."</td> <td>".$row['ReviewTitle']."</td> <td>".$row['UserName']."</td>
        <td>".$row['Rating']."</td> <td>".$row['Created']."</td>
            <td><form method='post'>
            <button type='submit'>Återställ</button>
            <input type='hidden' name='formname' value='".$formId."' /></form></td>
            </tr>";
    }
    $text .= "</table>";
    return $text;
}

function GenerateTableWithFlaggedAuthors($authors)
{
    $text = "";
    if (empty($authors))
    {
        return $text;
    }
        $text .= "<table><tr> <th>Förnamn</th> <th>Efternamn</th> <th>Återställ</th></tr>";
    foreach ($authors as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['Firstname']."</td>";
        $text.= "<td>".$row['Lastname']."</td>";
        $text.= "<td>";
        $formId = uniqid($row['Id'],true);
        $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."author/unflag",
        "authorId"=>$row['Id']);
            $text.= "<td><form method='post'><button type='submit'>Återställ</input>
            <input type='hidden' name='formname' value='".$formId."' />
            </form></td>";

        $text.= "</tr>";
    }
    $text .= "</table>";
    return $text;
}
?>