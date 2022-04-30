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
    "userId"=>$formData['User']['Id']);
    //Här genereras en fråga text och 4 alternativ och ett val om vilket svar som är korrekt.
    $text = "";
    $text .= "<form><table><tr> <th></th> <th></th> </tr>";
    for ($i=0; $i < $formData['NumberOfQ']; $i++) 
    { 
        $text .= "<tr><td>Fråga ".($i+1)."</td><td></td></tr>";
        $text .= "<tr> <td><label for='question_".$i."'>Fråga</label></td> <td><textarea id='question_".$i."' class='form-control' name='question[]' required ></textarea></td> </tr>";
        $text .= "<tr> <td><label for='answer1_".$i."'>Svar 1</label></td> <td><input type='text' class='form-control' id='answer1_".$i."' name='answer1[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer2_".$i."'>Svar 2</label></td> <td><input type='text' class='form-control' id='answer2_".$i."' name='answer2[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer3_".$i."'>Svar 3</label></td> <td><input type='text' class='form-control' id='answer3_".$i."' name='answer3[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer4_".$i."'>Svar 4</label></td> <td><input type='text' class='form-control' id='answer4_".$i."' name='answer4[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='realanswer_".$i."'>Svar</label></td> <td><input type='text' class='form-control' id='realanswer_".$i."' name='realanswer[]' required /></td> </tr>";
        $text .= "<tr> <td> </td> <td> </td> </tr>";
    }
    $text .= "<input type='submit' value='Spara' /><input type='hidden' name='formname' value='".$formId."' /></form>";
    return $text;
}
?>