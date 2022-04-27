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
    $text .= "<style>
    .img-sm {
        width: 46px;
        height: 46px;
    }
    
    
    .media-block .media-left {
        display: block;
        float: left;
    }
    
    .media-block .media-right {
        float: right;
    }
    
    .media-block .media-body {
        display: block;
        overflow: hidden;
        width: auto;
    }

    .text-muted, a.text-muted:hover, a.text-muted:focus {
        color: #acacac;
    }
    .text-sm {
        font-size: 0.9em;
    }
    .text-5x, .text-4x, .text-5x, .text-2x, .text-lg, .text-sm, .text-xs {
        line-height: 1.25;
    }
    </style>";

    /*
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
    }*/


    //KOMMENTARER
    foreach ($commentArr as $key => $commentrow) {
        /*
        $text.= "<tr>";
        $text.= "<td>".$commentrow['UserName']."</td>";
        $text.= "<td>".$commentrow['Created']."</td>";
        $text.= "<td>".$commentrow['Comment']."</td>";
        */

    
        $text .="<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'>";
        $text .="<div class='container bootdey'>";
        $text .="<div class='col-md-12 bootstrap snippets'>";
        $text .="<div class='panel'>";
        $text .="  <div class='panel-body'>";
        $text .="  <div class='media-block'>";
        $text .="  <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='../img/books/noimage.jpg'></a>";
        $text .="   <div class='media-body'>";
        $text .="    <div class='mar-btm'>";
        $text .="      <a href='#' class='btn-link text-semibold media-heading box-inline'>".$commentrow['UserName']."</a>";
        $text .="     <p class='text-muted text-sm'>".$commentrow['Created']."</p>";
        $text .="   </div>";
        $text .="   <p>".$commentrow['Comment']."</p>";
        $text .="   <div class='pad-ver'>";
        $text .="     <div class='btn-group'>";
        $text .="       <a class='btn btn-sm btn-default btn-hover-success' href='#'><i class='fa fa-thumbs-up'></i></a>";
        $text .="       <a class='btn btn-sm btn-default btn-hover-danger' href='#'><i class='fa fa-thumbs-down'></i></a>";
        $text .="     </div>";
        $text .="     <a class='btn btn-sm btn-default btn-hover-primary' href='#'>Svara</a>";
        $text .="   </div>";
        $text .="   <hr>";
        $text .="  </div>";
        $text .="</div>";
        $text .="</div>";



        /* SVARA DROPDOWN
        $text .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>";
        $text .= "<script src='".prefix."js/OpenFilterList.js'></script>";
        $text .= "<button class='toggle-button btn'>Svara</button>";
        $text .= "<form method='post' action='".prefix."review/replycomment'>";
        $text .= "<ul style='display:none;'>";
        $text .= "<li>";
        $text .="<input class='form-control' type='text' name='reply' placeholder='reply...'/>";
        $text .="<button class='btn btn-primary' type='submit' name='id' value='".$commentrow['Id']."'>svara</button>";
        $text .= "</li>";
        $text .="</ul>";
        $text .="</form>";
        */

        /*
        if (str_contains($role,"Admin"))
        {
            $text.= "<td><form method='post' action='".prefix."comments/edit'><button type='submit' name='id' value='".$commentrow['Id']."'>Edit</input>
            </form></td>";
            $text.= "<td><form method='post' action='".prefix."comments/delete'><button type='submit' name='id' value='".$commentrow['Id']."'>Radera</input>
            </form></td>";
        }
        elseif(str_contains($role,"Moderator"))
        {
            $text.= "<td><form method='post' action='".prefix."comment/flag'><button type='submit' name='id' value='".$commentrow['Id']."'>Flagga</input>
            <input type='hidden' name='ReviewId' value='".$commentrow['ReviewId']."' /></form></td>";
        }
        $text.= "<form method='post' action='".prefix."review/replycomment'><td><input type='text' name='reply' placeholder='reply...'>";
        $text.= "<button type='submit' name='id' value='".$commentrow['Id']."'>svara</form></td>";
        $text.= "</tr>";
*/

        //REPLIES  
        foreach ($replyArr as $key => $replyrow) 
        {
            if($replyrow['CommentId'] == $commentrow['Id'])
            {
                /*
                $text.= "<td>".$replyrow['UserName']."</td>";
                $text.= "<td>".$replyrow['Created']."</td>";
                $text.= "<td>".$replyrow['Reply']."</td>";
                $text.= "<td></td>";*/
                
                $text .="   <div>";
                $text .="     <div class='media-block'>";
                $text .="     <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='../img/books/noimage.jpg'></a>";
                $text .="       <div class='media-body'>";
                $text .="         <div class='mar-btm'>";
                $text .="          <a href='#' class='btn-link text-semibold media-heading box-inline'>".$replyrow['UserName']."</a>";
                $text .="           <p class='text-muted text-sm'>".$replyrow['Created']."</p>";
                $text .="        </div>";
                $text .="          <p>".$replyrow['Reply']."</p>";
                $text .="         <hr>";
                $text .="        </div>";
                $text .="      </div>";
                $text .="   </div>";
/*
                if (str_contains($role,"Admin"))
                {
                $text.= "<td><form method='post' action='".prefix."reply/edit'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Edit</input>
                </form></td>";
                $text.= "<td><form method='post' action='".prefix."reply/delete'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Radera</input>
                </form></td>";
                }
                elseif(str_contains($role,"Moderator"))
                {
                $text.= "<td><form method='post' action='".prefix."reply/flag'><button type='submit' name='id' value='".$replyrow['ReplyId']."'>Flagga</input>
                <input type='hidden' name='ReviewId' value='".$commentrow['ReviewId']."' />
                </form></td>";
                }
                */
            }
        }
    }


    return $text;
}


?>
