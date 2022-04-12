<?php


    function AddNewReview(/*$bookArr*/)
    {
        $text = "<h1>Skriv en Review</h1>";
        $text .= "<form method='post' action='".prefix."review/addreview'>";
        $text .= "<table>";

        $text .= "<tr><td><input type='hidden' id='bookid' name='BookId'/></td></tr>";  
        $text .= "<tr><td><input type='hidden' id='userid' name='UserId'/></td></tr>"; 
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










?>