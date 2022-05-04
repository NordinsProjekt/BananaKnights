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
    $text .= "<div class='container' style='text-align: center;'>";
    $text .= "<h1 class='display-4' style='padding: 40px 0 30px 0;'>Skapa Quiz</h1>";
    $text .= "<form method='post' style='padding: 0 0 0 500px;'>";
    $text .= "<table><tr> <th></th> <th></th> </tr>";
    $text .= "<tr> <td><label for='title'>Titel</label></td> <td><input type='text' class='form-control' id='title' name='title' required /></td> </tr>";
    $text .= "<tr> <td><label for='antalQ'>Antal frågor</label></td> <td><input type='number' class='form-control' id='antalQ' name='antalQ' required /></td> </tr>";
    $text .= "<tr><td><input type='hidden' name='formname' value='".$formId."' /><input type='submit' class='btn btn-primary' value='Spara' /></td> <td></td></tr>";
    $text .= "</table></form>";
    $text .= "</div>";
    return $text;
}

function QuestionForm($formData)
{
    if ($formData['NumberOfQ'] <=0)
    {
        return "<h1>Antal frågor < 0</h1>";
    }
    $formId = uniqid($formData['User']['Id'],true);
    //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
    $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/savequestions",
    "userId"=>$formData['User']['Id'],"NumberOfQuestions"=>$formData['NumberOfQ'],"QuizId"=>$formData['QuizId']);
    //Här genereras en fråga text och 4 alternativ och ett val om vilket svar som är korrekt.
    $text = "";
    $text .= "<div class='container' style='text-align: center;'>";
    $text .= "<form method='post'><div id='quizformparent'>";
    for ($i=0; $i < $formData['NumberOfQ']; $i++) 
    { 
        $text .= "<div class='quizformchild'><table><tr> <th></th> <th></th> </tr>";
        $text .= "<tr><td><h2>Fråga ".($i+1)."</h2></td><td></td></tr>";
        $text .= "<tr> <td><label for='question_".$i."'>Fråga</label></td> <td><textarea id='question_".$i."' class='form-control' name='question[]' required ></textarea></td> </tr>";
        $text .= "<tr> <td><label for='answer1_".$i."'>Svar 1</label></td> <td><input type='text' class='form-control' id='answer1_".$i."' name='answer1[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer2_".$i."'>Svar 2</label></td> <td><input type='text' class='form-control' id='answer2_".$i."' name='answer2[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer3_".$i."'>Svar 3</label></td> <td><input type='text' class='form-control' id='answer3_".$i."' name='answer3[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='answer4_".$i."'>Svar 4</label></td> <td><input type='text' class='form-control' id='answer4_".$i."' name='answer4[]' required /></td> </tr>";
        $text .= "<tr> <td><label for='realanswer_".$i."'>Rätta svaret</label></td> <td><select class='form-select' id='realanswer_".$i."' name='realanswer[]'>
        <option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select></td> </tr>";
        $text .= "<tr> <td> </td> <td> </td> </tr></table></div>";
    }
    $text .= "</div><table><tr><td><input type='submit' class='btn btn-outline-primary' value='Spara' /><input type='hidden' name='formname' value='".$formId."' /></td><td></td></tr></table></form>";
    $text .= "</div>";
    return $text;
}

function ShowAllQuiz($quiz,$role)
{
    $text = "";
    //TODO lägga in roller kontroll
    $text = "<h1 class='display-4' style='text-align:center; padding: 10px 0 20px 0'>Visa alla Quiz</h1>";
    $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr><th onclick='sortTable(0)'>Quiztitel</th> <th onclick='sortTable(1)'>Användare</th> <th onclick='sortTable(2)'>Skapad</th>";
    $text .= "<th>Visa</th>";
    if (str_contains($role,"Moderator"))
    {
        $text .= "<th>Flagga</th>";
    }
    // if (str_contains($role,"Admin"))
    // {
    //     $text .= "<th>Radera</th>";
    // }
    $text .= "</tr>";
    foreach ($quiz as $key => $row) {
        $text .= "<tr>
        <td>".$row['Title']."</td> <td>".$row['UserName']."</td> <td>".$row['Created']."</td> <td><form method='post' action='".prefix."quiz/show'>
        <button class='btn btn-outline-primary' type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
        if (str_contains($role,"Moderator"))
        {
            $formId = uniqid($row['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."quiz/flagged",
            "QuizId"=>$row['Id'],"BookId"=>$row['BookId']);
            $text .= "<td><form method='post'>
            <button class='btn btn-outline-warning' type='submit' name='id' value='".$row['Id']."'>Flagga</button>
            <input type='hidden' name='formname' value='".$formId."' /'></form></td>";
        }

        // if (str_contains($role,"Admin"))
        // {
        //     $text .= "
        //     <td><form method='post' action='".prefix."quiz/delete'>
        //     <button type='submit' name='id' value='".$row['Id']."'>Radera</button></form></td>
        //     </tr>";
        // }
    }
    $text .= "</table>";
    return $text;
}

function ShowAllQuizAllBooks($quiz)
{
    $text = "";
    $text .= "<h1 class='display-4' style='text-align: center; padding: 50px; 0 20px 0;'>Visa alla quiz</h1>";
    if (empty($quiz))
    {
        return $text;
    }
    $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr> <th onclick='sortTable(0)'>Bok titel</th> <th onclick='sortTable(1)'>Quiz titel</th> <th>Användarnamn</th> <th>Visa</th>
    </tr>";
    foreach ($quiz as $key => $row) {
        $text.= "<tr>";
        $text.= "<td>".$row['BookTitle']."</td>";
        $text.= "<td>".$row['Title']."</td>";
        $text.= "<td>".$row['UserName']."</td>";
        $text.= "<td><form method='post' action='".prefix."quiz/show'>
            <button class='btn btn-outline-primary' type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
        $text.= "</tr>";
    }
    $text .= "</table>";
    $text .= "<script src='".prefix."js/sortMe.js'></script>";
    return $text;
}
function ShowQuiz($formData,$user)
{
    $text = "";
    $text .= "<h1 class='display-4' style='padding: 40px 0 30px 0; text-align: center;'>Quiz</h1>";
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
    $text .= "</div>";
    $text .= "<div style='width: 100%; display: flex; justify-content: center; padding: 10px 0 100px 0'>";
    $text .= "<input type='hidden' name='QuizId' value='".$formData['Quiz']['Id']."' />";
    $text .= "<input style='width: 53%;' type='submit' class='btn btn-outline-primary' value='Skicka' />";
    $text .= "</div>";
    $text .= "</form>";
    return $text;
}
?>