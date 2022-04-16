<?php


    function AddNewReview($book)
    {
        $text = "<h1>Skriv en Review</h1>";
        $text .= "<h2>Recension för ".$book['Title']." (".$book['PublicationYear'].")</h2>";
        $text .= "<form method='post' action='".prefix."review/addreview'>";
        $text .= "<table>";

        $text .= "<tr><td><input type='hidden' id='bookid' name='id' value='".$book['Id']."' /></td></tr>";  
        $text .= "<tr><td><label for='title'>Title</label></td><td><input type='text' id='title' name='Title' /></td> </tr>"; 
        $text .= "<tr><td><label for='text'>Text</label></td><td><textarea id='ReviewText' name='Text' rows='10' cols='50'></textarea></td></tr>"; 
        $text .= "<tr><td><label for='rating'>Rating</label></td><td><select id='rating' name='Rating'>";
        $text .= "<option value=1>1</option>";
        $text .= "<option value=2>2</option>";
        $text .= "<option value=3>3</option>";
        $text .= "<option value=4>4</option>";
        $text .= "<option value=5>5</option></select>";
        $text .= "<tr> <td></td> <td><input type='submit' name='addreview' value='add'/></td></tr>";
        $text .= "</table></form>";
        return $text;
    }
    function ShowReview($review,$role)
    {
        $text = "<h1>Visa enskild recension</h1>";
        $text .= "<h2>".$review['ReviewTitle']."</h2>";
        $text .= "<div width='300px'>".$review['ReviewText']."</div>";
        $text .= "<p>Skriven av: ".$review['UserName']."</p>";
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
        $_SESSION['ReviewId'] = $review['Id'];
        return $text;
    }
    function ShowAllReviews($result,$role)
    {
        //TODO lägga in roller kontroll
        $text = "<h1>Visa alla reviews</h1>";
        if ($role != "Admin")
        {
            $text .= "<table><tr> <th></th> <th>Boktitel</th> <th>Titel</th> <th>Användare</th> <th>Betyg</th ><th>Skapad</th> 
            <th>Visa</th></tr>";
        }
        else
        {
            $text .= "<table><tr> <th></th> <th>Boktitel</th> <th>Titel</th> <th>Användare</th> <th>Betyg</th ><th>Skapad</th> 
            <th>Visa</th> <th>Edit</th> <th>Radera</th> </tr>";
        }

        foreach ($result as $key => $row) {
            if (file_exists("img/books/". $row['BookImagePath']))
            {
                $pictures = scandir("img/books/". $row['BookImagePath']);
                $imageLink = prefix."img/books/". $row['BookImagePath'] ."/". $pictures[2];
            }
            else
            {
                $imageLink = prefix."img/books/noimage.jpg";
            }
            $text .= "<tr><td><img src='".$imageLink."' alt='book cover' height='100px' /></td>
            <td>".$row['BookTitle']."</td> <td>".$row['ReviewTitle']."</td> <td>".$row['UserName']."</td>
            <td>".$row['Rating']."</td> <td>".$row['Created']."</td> <td><form method='post' action='".prefix."review/show'>
            <button type='submit' name='id' value='".$row['Id']."'>Visa</button></form></td>";
            if ($role == "Admin")
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