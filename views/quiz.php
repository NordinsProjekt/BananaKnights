<?php
function QuizForm($formData)
{
    $text = "";
    //Om användaren inte är inloggad eller inte har några roller
    if ($formData['User']['Roles'] == "")
    {
        return $text;
    }

    $formId = uniqid($formData['User']['Id'],true);
    //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
    $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/savequiz",
    "userId"=>$formData['User']['Id'],"bookId"=>$formData['Book']['Id']);
    //Ha med sessionform spara bookid
    $text = "";
    $text .= "<h1>Skapa Quiz</h1>";
    $text .= "<form method='post'>";
    $text .= "<table><tr> <th></th> <th></th> </tr>";
    $text .= "<tr> <td><label for='title'>Titel</label></td> <td><input type='text' id='title' name='title' required /></td> </tr>";
    $text .= "<tr> <td><label for='antalQ'>Antal frågor</label></td> <td><input type='number' id='antalQ' name='antalQ' required /></td> </tr>";
    $text .= "<tr> <td><label for='accessability'>Typ av Quiz</label></td> <td><select name='access' id='accessability'><option value='private'>Privat</option>
    <option value='public'>Publik</option></select></td> </tr>";
    $text .= "<tr> <td><label for='enddate'>Avslut</label></td> <td><input type='date' id='enddate' name='enddate' placeholder='Möjlighet att sätta slutdatum' /></td> </tr>";
    $text .= "<tr><td><input type='hidden' name='formname' value='".$formId."' /><input type='submit' value='Spara' /></td> <td></td></tr>";
    $text .= "</table></form>";
    return $text;
}

function QuestionForm($formData)
{
    $formId = uniqid($formData['User']['Id'],true);
    //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
    $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/savequestions",
    "userId"=>$formData['User']['Id'],"NumberOfQuestions"=>$formData['NumberOfQ'],"QuizId"=>$formData['QuizId']);
    //Här genereras en fråga text och 4 alternativ och ett val om vilket svar som är korrekt.
    $text = "";
    $text .= "<form method='post'><table><tr> <th></th> <th></th> </tr>";
    for ($i=0; $i < $formData['NumberOfQ']; $i++) 
    { 
        $text .= "<tr><td>Fråga ".($i+1)."</td><td></td></tr>";
        $text .= "<tr> <td><label for='question_".$i."'>Fråga</label></td> <td><textarea id='question_".$i."' class='form-control' name='question[]' required ></textarea></td> </tr>";
        $text .= "<tr> <td><label for='answer1_".$i."'>Svar 1</label></td> <td><input type='text' class='form-control' id='answer1_".$i."' name='answer1[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer2_".$i."'>Svar 2</label></td> <td><input type='text' class='form-control' id='answer2_".$i."' name='answer2[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer3_".$i."'>Svar 3</label></td> <td><input type='text' class='form-control' id='answer3_".$i."' name='answer3[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer4_".$i."'>Svar 4</label></td> <td><input type='text' class='form-control' id='answer4_".$i."' name='answer4[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='realanswer_".$i."'>Rätta svaret</label></td> <td><select class='form-control' id='realanswer_".$i."' name='realanswer[]'>
        <option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select></td> </tr>";
        $text .= "<tr> <td> </td> <td> </td> </tr>";
    }
    $text .= "<tr><td><input type='submit' value='Spara' /><input type='hidden' name='formname' value='".$formId."' /></td><td></td></tr></table></form>";
    return $text;
}

function ShowAllQuiz($quiz,$role)
{
    $text = "";
    //TODO lägga in roller kontroll
    $text = "<h1>Visa alla Quiz</h1>";
    $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr><th onclick='sortTable(0)'>Quiztitel</th> <th onclick='sortTable(1)'>Användare</th> <th onclick='sortTable(2)'>Skapad</th>";
    $text .= "<th>Visa</th>";
    if (str_contains($role,"Moderator"))
    {
        $text .= "<th>Flagga</th>";
    }
    if (str_contains($role,"Admin"))
    {
        $text .= "<th>Radera</th>";
    }
    $text .= "</tr>";
    foreach ($quiz as $key => $row) {
        $text .= "<tr>
        <td>".$row['Title']."</td> <td>".$row['UserName']."</td> <td>".$row['Created']."</td> <td><form method='post' action='".prefix."quiz/show'>
        <button type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
        if (str_contains($role,"Moderator"))
        {
            $formId = uniqid($row['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/flagged",
            "reviewId"=>$row['Id']);
            $text .= "<td><form method='post'>
            <button type='submit' name='id' value='".$row['Id']."'>Flagga</button>
            <input type='hidden' name='formname' value='".$formId."' /'></form></td>";
        }
        if (str_contains($role,"Admin"))
        {
            $text .= "
            <td><form method='post' action='".prefix."quiz/delete'>
            <button type='submit' name='id' value='".$row['Id']."'>Radera</button></form></td>
            </tr>";
        }
    }
    $text .= "</table>";
    return $text;
}
function ShowQuiz($formData,$user)
{
    $text = "";
    $text .= "<h1>Quiz</h1>";
    $text .= "<form method='post' action='".prefix."quiz/checkanswers'><div id='quizparent'>";
    for ($i=0; $i < count($formData['Quiz']['Questions']); $i++) 
    { 
        $text .= "<div class='quizchild'><h2>".$formData['Quiz']['Questions'][$i]['Question']."</h2>";
        $text .= "<fieldset id='answer".$i."'>";
            $text .= "<input type='radio' id='".$i."answer1' name='answer".$i."' value='1' required /><label for='".$i."answer1'>".$formData['Quiz']['Questions'][$i]['Alt1']."</label><br />";
            $text .= "<input type='radio' id='".$i."answer2' name='answer".$i."' value='2' /><label for='".$i."answer2'>".$formData['Quiz']['Questions'][$i]['Alt2']."</label><br />";
            $text .= "<input type='radio' id='".$i."answer3' name='answer".$i."' value='3' /><label for='".$i."answer3'>".$formData['Quiz']['Questions'][$i]['Alt3']."</label><br />";
            $text .= "<input type='radio' id='".$i."answer4' name='answer".$i."' value='4' /><label for='".$i."answer4'>".$formData['Quiz']['Questions'][$i]['Alt4']."</label><br />";
        $text .= "</fieldset></div>";
    }
    $text .= "</div><input type='hidden' name='QuizId' value='".$formData['Quiz']['Id']."' />";
    $text .= "<input type='submit' value='Skicka' />";
    $text .= "</form>";
    return $text;
}
?>