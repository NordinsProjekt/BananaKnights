<?php

function CreateNewComment($arr)
    {
        $text  = "";
        $text .= "<div style='width: 100%; display: flex; justify-content: center; padding: 10px 0 50px 0;'>";
        $text .= "<div style='width: 60rem;'>";
        $text .= "<form method='post' action='".prefix."review/addcomment'>";
        $text .= "<div class='container bootdey'>";
        $text .= "<div class='col-md-12 bootstrap snippets'>";
        $text .= "<div class='panel'>";
        $text .= "<div class='panel-body'>";
        $text .= "<textarea name='Comment' id='txtComment' class='form-control' rows='2' placeholder='Lägg till en kommentar...' ></textarea>";
        $text .= "<div class='mar-top clearfix'>";
        $text .= "<button style='margin-top: 10px;' class='btn btn-outline-primary' type='submit' name='id' value='".$arr['Id']."'>Submit</input>";
        $text .= "</div>";
        $text .= "</div>";
        $text .= "</div>";
        $text .= "</form><br><br>";
        $text .= "</div>";
        $text .= "</div>";
        $text .= "<script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>";
        return $text;
}

function ShowAllCommentsReplies($commentArr,$replyArr,$role)
{

    $text = "";

    //KOMMENTARER
    foreach ($commentArr as $key => $commentrow) {


        $text .="<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'>";
        $text .="<div class='container bootdey' style='padding-left: 0'>";
        $text .="<div class='col-md-12 bootstrap snippets'>";
        $text .="<div class='panel'>";
        $text .="  <div class='panel-body'>";
        $text .="  <div class='media-block'>";
        $text .="  <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='".prefix."img/profile/noimage.png'></a>";
        $text .="   <div class='media-body'>";
        $text .="    <div class='mar-btm'>";
        $text .="      <a href='#' class='btn-link text-semibold media-heading box-inline'>".$commentrow['UserName']."</a>";
        $text .="     <p class='text-muted text-sm'>".$commentrow['Created']."</p>";
        $text .="   </div>";
        $text .="   <p>".$commentrow['Comment']."</p>";
        $text .="   <div class='pad-ver'>";

        $text .= "<div><form method='post' action='".prefix."review/replycomment'>";
        $text .= "<ul style='display:none;'>";
        $text .="<textarea class='form-control' type='text' name='reply' placeholder='reply...'></textarea>";
        $text .="<button class='btn btn-primary' type='submit' name='id' value='".$commentrow['Id']."'>svara</button>";
        $text .="</ul>";
        $text .="</form>";
        $text .=" <a class='btn btn-sm btn-default btn-hover-primary toggle-button' style='color: white; float:left;'>Svara</a></div>";
        if (str_contains($role,"Admin"))
        {
            $text .= "<div><form method='post' action='".prefix."review/editcomment'>";
            $text .=" <a class='btn btn-sm btn-default btn-hover-primary toggle-button' style='color: white; float:left;'>Edit</a>";
            $text .= "<ul style='display:none;'>";
            $text .="<textarea class='form-control' type='text' name='reply' placeholder='Texten här ersätter den ovanför'></textarea>";
            $text .="<button class='btn btn-primary' type='submit' name='id' value='".$commentrow['Id']."'>Spara</button>";
            $text .="</ul>";
            $text .="</form></div>";
        }
        if(str_contains($role,"Moderator"))
        {
            $text.= "<form method='post' action='".prefix."comment/flag'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$commentrow['Id']."'>Flagga</button>
            <input type='hidden' name='ReviewId' value='".$commentrow['ReviewId']."' /></form>";
        }
        $text .="<br /></div>";
        $text .="<hr>";

        //REPLIES  
        foreach ($replyArr as $key => $replyrow) 
        {
            if($replyrow['CommentId'] == $commentrow['Id'])
            {      
                $text .="   <div>";
                $text .="     <div class='media-block'>";
                $text .="     <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='".prefix."img/profile/noimage.png'></a>";
                $text .="       <div class='media-body'>";
                $text .="         <div class='mar-btm'>";
                $text .="          <a href='#' class='btn-link text-semibold media-heading box-inline'>".$replyrow['UserName']."</a>";
                $text .="           <p class='text-muted text-sm'>".$replyrow['Created']."</p>";
                $text .="        </div>";
                $text .="          <p>".$replyrow['Reply']."</p>";
                $text .="<div class='pad-ver'>";
                $text .="<div class='btn-group'>";
                if (str_contains($role,"Admin"))
                {
                    $text .= "<div><form method='post' action='".prefix."review/editreply'>";
                    $text .=" <a class='btn btn-sm btn-default btn-hover-primary toggle-button' style='color: white; float:left;'>Edit</a>";
                    $text .= "<ul style='display:none;'>";
                    $text .="<textarea class='form-control' type='text' name='reply' placeholder='Texten här ersätter den ovanför'></textarea>";
                    $text .="<button class='btn btn-primary' type='submit' name='id' value='".$replyrow['ReplyId']."'>Spara</button>";
                    $text .="</ul>";
                    $text .="</form></div>";
                }
                if(str_contains($role,"Moderator"))
                {
                $text.= "<form method='post' action='".prefix."reply/flag'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$replyrow['ReplyId']."'>Flagga</button>
                <input type='hidden' name='ReviewId' value='".$commentrow['ReviewId']."' />
                </form>";
                }
                $text .="</div>";
                $text .="         <hr>";
                $text .="        </div>";
                $text .="      </div>";
                $text .="   </div>";
    
            }
        }

        $text .="  </div>";
        $text .="</div>";
        $text .="</div>";
    }

    return $text;
}


?>
