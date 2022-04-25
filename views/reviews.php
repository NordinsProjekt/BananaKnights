<?php
    function AddNewReview($book)
    {
        $text = "<h1>Skriv en Review</h1>";
        $text .= "<h2>Recension för ".$book['Title']." (".$book['PublicationYear'].")</h2>";
        $text .= "<form method='post' action='".prefix."review/addreview'>";
        $text .= "<table>";

        $text .= "<tr><td><input type='hidden' id='bookid' name='id' value='".$book['Id']."' /></td></tr>";  
        $text .= "<tr><td><label for='title'>Title</label></td><td><input type='text' id='title' class='form-control' name='Title'  required/></td> </tr>"; 
        $text .= "<tr><td><label for='text'>Text</label></td><td><textarea id='ReviewText' class='form-control' name='Text' rows='10' cols='50' required></textarea></td></tr>"; 
        $text .= "<tr><td><label for='rating'>Rating</label></td><td><select id='rating' class='form-select' name='Rating'>";
        $text .= "<option value=1>1</option>";
        $text .= "<option value=2>2</option>";
        $text .= "<option value=3>3</option>";
        $text .= "<option value=4>4</option>";
        $text .= "<option value=5>5</option></select>";
        $text .= "<tr> <td></td> <td><input type='submit' name='addreview' class='btn btn-primary' value='Spara'/></td></tr>";
        $text .= "</table></form>";
        return $text;
    }

    function EditReview($formData,$user)
    {
        $text = "";
        //Säkerhetskontroll även i viewn
        if (str_contains($user['Roles'],"Admin") || $user['Id'] == $formData['Review']['UserId'])
        {
            //Behöver mer data för att få in book title och årtal också.            
            $formId = uniqid($formData['Review']['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/saveeditreview",
                "reviewId"=>$formData['Review']['Id']);

            $text = "<h1>Editera ".$formData['Review']['ReviewTitle']."</h1>";
            $text .= "<h2>Recension för ".$formData['Book']['Title']." (".$formData['Book']['PublicationYear'].")</h2>";
            $text .= "<form method='post'>";
            $text .= "<table>";
            $text .= "<tr><td><label for='title'>Title</label></td>
            <td><input type='text' id='title' class='form-control' name='Title' value='".$formData['Review']['ReviewTitle']."' required /></td> </tr>"; 
            $text .= "<tr><td><label for='text'>Text</label></td>
            <td><textarea id='ReviewText' class='form-control' name='Text' rows='10' cols='50' required >".$formData['Review']['ReviewText']."</textarea></td></tr>"; 
            $text .= "<tr><td><label for='rating'>Rating</label></td><td><select id='rating' class='form-select' name='Rating'>";
            for ($i=1; $i <6 ; $i++) { 
                if ($formData['Review']['Rating'] == $i)
                {
                    $text .= "<option value='".$i."' selected>".$i."</option>";
                }
                else
                {
                    $text .= "<option value='".$i."'>".$i."</option>";
                }
            }
            $text .= "</select>";
            $text .= "<tr><td>Skapad av</td> <td>".$formData['Review']['UserName']."</td> </tr>";
            $text .= "<tr><td>Skapad</td> <td>".$formData['Review']['Created']."</td> </tr>";
            $text .= "<tr> <td></td> <td><input type='hidden' name='formname' value='".$formId."' /><input type='submit' class='btn btn-primary' name='saveeditreview' value='Spara'/></td></tr>";
            $text .= "</table></form>";
        }

        return $text;
    }
    function ShowReview($review,$role)
    {
        //$review['ReviewText'] = str_replace('\n','<br />',$review['ReviewText']);
        $text = "<h1>Visa enskild recension</h1>";
        $text .= "<h2>".$review['ReviewTitle']."</h2>";
        $text .= "<div width='300px'>".$review['ReviewText']."</div>";
        $text .= "<br /><p><b>Skriven av: </b>".$review['UserName']."</p>";
        $text .= "<p>Skapad: ".$review['Created']."</p>";
        if ($role != "")
        {
            $text .= "<form method='post' action='".prefix."review/usefull'>";
            if ($review['Usefull'])
            {
                $text .= "<button type='submit' name='id' value='".$review['Id']."' style='background-color:green'>Hjälpsam</button></form>";
            }
            else
            {
                $text .= "<button type='submit' name='id' value='".$review['Id']."'>Hjälpsam</button></form>";
            }
        }
        if (str_contains($role,"Moderator") && $review['Flagged'] == 0)
        {
            //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
            $formId = uniqid($review['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/flagged",
            "reviewId"=>$review['Id']);
            $text .= "<form method='post' action='lol'>
            <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Anmäl</button></form>";
        }
        if (str_contains($role,"Admin") && $review['Flagged'] == 1)
        {
            //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
            $formId = uniqid($review['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/unflag",
            "reviewId"=>$review['Id']);
            $text .= "<form method='post' action='lol'>
            <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Återställ</button></form>";
        }
        $_SESSION['ReviewId'] = $review['Id'];
        return $text;
    }

    function ShowAllReviews($result,$role)
    {
        //TODO lägga in roller kontroll
        $text = "<h1>Visa alla reviews</h1>";
        if (str_contains($role,"Admin"))
        {
            $text .= "<table><tr> <th></th> <th>Boktitel</th> <th>Titel</th> <th>Användare</th> <th>Betyg</th ><th>Skapad</th> 
            <th>Visa</th> <th>Edit</th> <th>Radera</th> </tr>";
        }
        else
        {
            $text .= "<table><tr> <th></th> <th>Boktitel</th> <th>Titel</th> <th>Användare</th> <th>Betyg</th ><th>Skapad</th> 
            <th>Visa</th></tr>";
        }

        foreach ($result as $key => $row) {
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
            <td>".$row['Rating']."</td> <td>".$row['Created']."</td> <td><form method='post' action='".prefix."review/show'>
            <button type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
            if (str_contains($role,"Admin"))
            {
                $text .= "
                <td><form method='post' action='".prefix."review/edit'>
                <button type='submit' name='id' value='".$row['Id']."'>Edit</button></form></td>
                <td><form method='post' action='".prefix."review/delete'>
                <button type='submit' name='id' value='".$row['Id']."'>Radera</button></form></td>
                </tr>";
            }
            else
            {
                $text .= "<td></td><td></td>";
            }
        }
        $text .= "</table>";
        return $text;
    }
?>