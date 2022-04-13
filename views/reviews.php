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

    function ShowAllReviews($result,$role)
    {
        //TODO ShowAllReviews fixa!!
        $text = "<h1>Visa alla reviews</h1>";
        for ($i=0; $i < count($result); $i++) { 
            var_dump($result[$i]);echo "<br /><br />";
        }
                

        // if ($role == "Admin")
        // {
        //     $text .= "<table><tr> <th></th> <th>Beskrivning</th> <th>Skapad</th> <th>Visa</th> <th>Edit</th> <th>Radera</th></tr>";
        // }
        // else
        // {
        //     $text .= "<table><tr> <th>Namn</th> <th>Beskrivning</th> <th>Visa</th></tr>";
        // }
        
        // foreach ($result as $key => $row) {
        //     $text.= "<tr>";
        //     $text.= "<td>".$row['Name']."</td>";
        //     $text.= "<td>".$row['Description']."</td>";
        //     $text.= "<td>".$row['Created']."</td>";
        //     $text.= "<td><form method='post' action='".prefix."books/showgenre'><button type='submit' name='id' value='".$row['Id']."'>Visa</input>
        //     </form></td>";
        //     if ($role == "Admin")
        //     {
        //         $text.= "<td><form method='post' action='".prefix."books/editgenre'><button type='submit' name='id' value='".$row['Id']."'>Edit</input>
        //         </form></td>";
        //         $text.= "<td><form method='post' action='".prefix."books/deletegenre'><button type='submit' name='id' value='".$row['Id']."'>Radera</input>
        //         </form></td>";
        //     }
        //     $text.= "</tr>";
        // }
        // if ($role == "Admin")
        // {
        //     $text.= "</table><form method='post' action='".prefix."books/creategenre'><button type='submit'>Skapa ny genre</button></form>";
        // }
        // return $text;
    }
?>