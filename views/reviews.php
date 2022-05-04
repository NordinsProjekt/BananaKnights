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
        $text .= "<div class='container' style='text-align: center;'>";
        //Säkerhetskontroll även i viewn
        if (str_contains($user['Roles'],"Admin") || $user['Id'] == $formData['Review']['UserId'])
        {
            //Behöver mer data för att få in book title och årtal också.            
            $formId = uniqid($formData['Review']['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/saveeditreview",
                "reviewId"=>$formData['Review']['Id']);

            $text .= "<h1 class='display-4' style='padding: 40px 0 30px 0;'>Editera ".$formData['Review']['ReviewTitle']."</h1>";
            $text .= "<h2>Recension för ".$formData['Book']['Title']." (".$formData['Book']['PublicationYear'].")</h2>";
            $text .= "<form method='post' style='padding: 0 0 0 420px;'>";
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
            $text .= "<tr> <td></td> <td><input type='hidden' name='formname' value='".$formId."' /><input type='submit' class='btn btn-outline-primary' name='saveeditreview' value='Spara'/></td></tr>";
            $text .= "</table></form>";
            $text .= "</div>";
        }

        return $text;
    }
    function ShowReview($review,$user)
    {
        //$review['ReviewText'] = str_replace('\n','<br />',$review['ReviewText']);
        $text = "";
        $text .= "<div style='width: 100%; display: flex; justify-content: center; padding: 50px 0 50px 0; text-align:center;'>";
        $text .= "<div style='width: 60rem;'>";
        $text .= "<h1>".$review['ReviewTitle']."</h1>";
        $text .= "<p><b>Betyg: </b>".$review['Rating']." av 5</p>";
        $text .= "<b>Review: </b><div width='300px'>".$review['ReviewText']."</div><br>";
        $text .= "<p><small class='text-muted'>Skapad den: ".$review['Created']."<br> skriven av: ".$review['UserName']."</small></p>";

        $text .= "<div style='width: 100%; display: flex; justify-content: center; padding-top: 0'>";
        if ($user['Roles'] != "")
        {
            $text .= "<form method='post' action='".prefix."review/usefull' style='padding-top: 24px;'>";
            if ($review['Usefull'])
            {
                $text .= "<button style='margin-right: 10px;' class='btn btn-success' type='submit' name='id' value='".$review['Id']."'>Hjälpsam</button></form>";
            }
            else
            {
                $text .= "<button style='margin-right: 10px;' class='btn btn-outline-success' type='submit' name='id' value='".$review['Id']."'>Hjälpsam</button></form>";
            }
        }
        if ($review['UserName'] == $user['Username'])
        {
            $text .= "<td><form method='post' action='".prefix."review/edit'>
                        <button style='margin-right: 10px;' class='btn btn-outline-warning' type='submit' name='id' value='".$review['Id']."'>Edit</button></form></td>";
        }
        if (str_contains($user['Roles'],"Moderator") && $review['Flagged'] == 0)
        {
            //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
            $formId = uniqid($review['Id'],true);
            $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/flagged",
            "reviewId"=>$review['Id']);
            $text .= "<form method='post' action='lol'>
            <input type='hidden' name='formname' value='".$formId."' /'><button style='margin-right: 10px;' class='btn btn-outline-warning' type='submit'>Flagga</button></form>";
        }
        $text .="</div>";

        $text .= "</div>";
        $text .= "</div>";
        $text.= "<hr>";
        // if (str_contains($user['Roles'],"Admin") && $review['Flagged'] == 1)
        // {
        //     //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
        //     $formId = uniqid($review['Id'],true);
        //     $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/unflag",
        //     "reviewId"=>$review['Id']);
        //     $text .= "<form method='post' action='lol'>
        //     <input type='hidden' name='formname' value='".$formId."' /'><button type='submit'>Återställ</button></form>";
        // }
        $_SESSION['ReviewId'] = $review['Id'];
    
        $text.= "<div style='text-align: center; padding: 15px 0 0 0'>";
        $text.= "<small>Vad tycker du om denna recension?</small>";
        $text.= "</div>";
        return $text;
    }

    function ShowAllReviews($result,$role)
    {
        //TODO lägga in roller kontroll
        $text = "<h1 class='display-4' style='text-align:center; padding: 10px 0 20px 0'>Visa alla reviews</h1>";
        $text .= "<table id='myTable' class='table table-bordered table-dark table-hover'><tr> <th></th> <th onclick='sortTable(1)'>Boktitel</th> <th onclick='sortTable(2)'>Titel</th> <th onclick='sortTable(3)'>Användare</th> <th onclick='sortTable(4)'>Betyg</th ><th onclick='sortTable(5)'>Skapad</th>";
        $text .= "<th>Visa</th>";
        if (str_contains($role,"Moderator"))
        {
            $text .= "<th>Flagga</th>";
        }
        if (str_contains($role,"Admin"))
        {
            $text .= "<th>Edit</th> <th>Radera</th>";
        }
        $text .= "</tr>";
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
            <button class='btn btn-outline-primary' type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
            if (str_contains($role,"Moderator"))
            {
                $formId = uniqid($row['Id'],true);
                $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."review/flagged",
                "reviewId"=>$row['Id']);
                $text .= "<td><form method='post'>
                <button class='btn btn-outline-warning' type='submit' name='id' value='".$row['Id']."'>Flagga</button>
                <input type='hidden' name='formname' value='".$formId."' /'></form></td>";
            }
            if (str_contains($role,"Admin"))
            {
                $text .= "
                <td><form method='post' action='".prefix."review/edit'>
                <button class='btn btn-outline-warning' type='submit' name='id' value='".$row['Id']."'>Edit</button></form></td>
                <td><form method='post' action='".prefix."review/delete'>
                <button class='btn btn-outline-danger' type='submit' name='id' value='".$row['Id']."'>Radera</button></form></td>
                </tr>";
            }
            else
            {
                if (isset($_SESSION['Username']))
                {
                    if ($row['UserName'] == $_SESSION['Username'])
                    {
                        $text .= "<td><form method='post' action='".prefix."review/edit'>
                        <button type='submit' name='id' value='".$row['Id']."'>Edit</button></form></td>";
                    }
                }

            }
        }
        $text .= "</table>";
        $text.= "<script src='".prefix."js/sortMe.js'></script>";
        return $text;
    }
    function ShowUserReviews($reviews)
    {
        $text = "";
        foreach ($reviews as $key => $row) {
            $text .= "<a href='".prefix."showreview?id=".$row['Id']."'>".$row['BookTitle']."("
            .$row['BookYear'].") ".$row['ReviewTitle']." Betyg: ".$row['Rating']." av 5</a><br />";
        }
        return $text;
    }
?>