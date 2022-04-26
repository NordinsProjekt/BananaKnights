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
    $text .= "<h2>Latest Comments<h2>";

    
    $text .="<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'>";

    $text .="<div class='container bootdey'>";
    $text .="<div class='col-md-12 bootstrap snippets'>";
    $text .="<div class='panel'>";
    $text .="  <div class='panel-body'>";


    $text .="  <div class='media-block'>";
    $text .="  <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='../img/books/noimage.jpg'></a>";
    $text .="   <div class='media-body'>";
    $text .="    <div class='mar-btm'>";
    $text .="      <a href='#' class='btn-link text-semibold media-heading box-inline'>Username</a>";
    $text .="     <p class='text-muted text-sm'>11 min ago</p>";
    $text .="   </div>";
    $text .="   <p>consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>";
    $text .="   <div class='pad-ver'>";
    $text .="     <div class='btn-group'>";
    $text .="       <a class='btn btn-sm btn-default btn-hover-success' href='#'><i class='fa fa-thumbs-up'></i></a>";
    $text .="       <a class='btn btn-sm btn-default btn-hover-danger' href='#'><i class='fa fa-thumbs-down'></i></a>";
    $text .="     </div>";
    $text .="     <a class='btn btn-sm btn-default btn-hover-primary' href='#'>Svara</a>";
    $text .="   </div>";
    $text .="   <hr>";

    $text .="   <div>";
    $text .="     <div class='media-block'>";
    $text .="     <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='../img/books/noimage.jpg'></a>";
    $text .="       <div class='media-body'>";
    $text .="         <div class='mar-btm'>";
    $text .="          <a href='#' class='btn-link text-semibold media-heading box-inline'>Username</a>";
    $text .="           <p class='text-muted text-sm'>7 min ago</p>";
    $text .="        </div>";
    $text .="          <p>Sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>";
    $text .="         <div class='pad-ver'>";
    $text .="           <div class='btn-group'>";
    $text .="           </div>";
    $text .="         </div>";
    $text .="         <hr>";
    $text .="        </div>";
    $text .="      </div>";
    $text .="</div>";
    $text .="</div>";
    $text .="</div>";
    $text .="</div>";


    $text .= "<div class='container'>";
    $text .= "<div class='row'>";
    $text .= "<div class='col'>";
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
        
        $text .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>";
        $text .= "<script src='".prefix."js/OpenFilterList.js'></script>";

        $text .="<div class='list-group' style='width: 20rem'>";
        $text .="<div class='list-group-item list-group-item-action d-flex gap-3 py-3' aria-current='true'>";
        $text .="<img src='../img/books/noimage.jpg' alt='twbs' class='rounded-circle flex-shrink-0' width='32' height='32'>";
        $text .="<div class='d-flex gap-2 w-100 justify-content-between'>";
        $text .="<div style='float: left;'>";
        $text .="<h6 class='mb-2'>".$commentrow['UserName']."<span style='color:white;'>gsdgsgdgs</span><small class='opacity-50 text-nowrap'>".$commentrow['Created']."</small></h6>";
        $text .="<p class='mb-0 opacity-75'><textarea disabled class='form-control' style='resize: none; min-width: 14rem;'>".$commentrow['Comment']."</textarea></p>";
        $text .= "<button class='toggle-button btn'>Svara</button>";

        $text .= "<form method='post' action='".prefix."review/replycomment'>";
        $text .= "<ul style='display:none;'>";
        $text .= "<li>";
        $text .="<input class='form-control' type='text' name='reply' placeholder='reply...'/>";
        $text .="<button class='btn btn-primary' type='submit' name='id' value='".$commentrow['Id']."'>svara</button>";
        $text .= "</li>";
        $text .="</ul>";
        $text .="</form>";

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
            $text.= "<td><form method='post' action='".prefix."comments/flag'><button type='submit' name='id' value='".$commentrow['Id']."'>Flagga</input>
            </form></td>";
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
                
                $text .="<img src='../img/books/noimage.jpg' alt='twbs' class='rounded-circle flex-shrink-0' width='22' height='22'>";
                $text .="<div class='d-flex gap-2 w-100 justify-content-between'>";
                $text .="<div style='float: left;'>";
                $text .="<h6 class='mb-2'>".$replyrow['UserName']."<span style='color:white;'>gsdgsgdgs</span><small class='opacity-50 text-nowrap'>".$replyrow['Created']."</small></h6>";
                $text .="<p class='mb-0 opacity-75'><textarea disabled class='form-control' style='resize: none; min-width: 14rem;'>".$replyrow['Reply']."</textarea></p>";
                $text .="</div>";
                $text .="</div>";
                $text .="</div>";
                

/*
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
                */
            }
        }
    }
    /*
    $text.= "</form></td>";*/
    
    $text .="</div>"; //container end grid
    $text .= "</div>"; //row grid end
    $text .= "</div>"; //column grid end
    return $text;
}


?>
