<?php

function CreateNewComment($arr)
{
        $text = "<form method='post' action='".prefix."review/addcomment'>";
        $text .= "<table><tr><th></th><th></th></tr>";
        $text .= "<tr> <td><label for='txtComment'>Kommentar</label></td> <td><input type='text' id='txtComment' 
                name='Comment' placeholder='Kommentar'/></td> </tr>";
        $text .= "<td><button type='submit' name='id' value='".$arr['Id']."'>Submit</input></td>";
        $text .= "</table></form>";
        return $text;
}

function ShowAllCommentsReplies($commentArr,$replyArr,$role)
{
    $text = "";
    if (str_contains($role,"Admin"))
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th> 
                <th>Edit</th> <th>Radera</th></tr>";
    }
    elseif(str_contains($role,"Moderator"))
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th> 
                <th>Flagga</th></tr>";
    }
    else
    {
        $text .= "<table><tr> <th>Username</th> <th>Skapad</th> <th>Kommentar</th></tr>";
    }

    //KOMMENTARER
    foreach ($commentArr as $key => $commentrow) {
        $text.= "<tr>";
        $text.= "<td>".$commentrow['UserName']."</td>";
        $text.= "<td>".$commentrow['Created']."</td>";
        $text.= "<td>".$commentrow['Comment']."</td>";
        

        if (str_contains($role,"Admin"))
        {
            $text.= "<td><form method='post' action='".prefix."comments/edit'><button type='submit' name='id' value='".$commentrow['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."comments/delete'><button type='submit' name='id' value='".$commentrow['Id']."'>Radera</input>
            </form></td>";
        }
        elseif(str_contains($role,"Moderator"))
        {
            $text.= "<td><form method='post' action='".prefix."comments/flag'><button type='submit' name='id' value='".$commentrow['Id']."'>Flagga</input>
            </form></td>";
        }
        $text.= "<form method='post' action='".prefix."review/replycomment'><td><input type='text' name='reply' placeholder='reply...'>";
        $text.= "<button type='submit' name='id' value='".$commentrow['Id']."'>svara</form></td>";
        $text.= "</tr>";


        //REPLIES  
        foreach ($replyArr as $key => $replyrow) 
        {
            if($replyrow['CommentId'] == $commentrow['Id'])
            {
                $text.= "";
                $text.= "<td>".$replyrow['UserName']."</td>";
                $text.= "<td>".$replyrow['Created']."</td>";
                $text.= "<td>".$replyrow['Reply']."</td>";
                $text.= "<td></td>";

                if (str_contains($role,"Admin"))
                {
                $text.= "<td><form method='post' action='".prefix."comments/edit'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Edit</input>
                </form></td>";
                $text.= "<td><form method='post' action='".prefix."comments/delete'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Radera</input>
                </form></td>";
                }
                elseif(str_contains($role,"Moderator"))
                {
                $text.= "<td><form method='post' action='".prefix."comments/flag'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Flagga</input>
                </form></td>";
                }
            }
        }
    }
    $text.= "</form></td>";
    
    return $text;
}





















?>