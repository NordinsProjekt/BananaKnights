<?php

function CreateNewComment($arr)
    {
        $text  = "";
        $text .= "<form method='post' action='".prefix."review/addcomment'>";
        $text .= "<div class='container bootdey'>";
        $text .= "<div class='col-md-12 bootstrap snippets'>";
        $text .= "<div class='panel'>";
        $text .= "<div class='panel-body'>";
        $text .= "<textarea name='Comment' id='txtComment' class='form-control' rows='2' placeholder='What are you thinking?' ></textarea>";
        $text .= "<div class='mar-top clearfix'>";
        $text .= "<button class='btn btn-sm btn-primary' type='submit' name='id' value='".$arr['Id']."'>Submit</input>";
        $text .= "</div>";
        $text .= "</div>";
        $text .= "</div>";
        $text .= "</form><br><br>";
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
        $text .="  <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='".prefix."img/books/noimage.jpg'></a>";
        $text .="   <div class='media-body'>";
        $text .="    <div class='mar-btm'>";
        $text .="      <a href='#' class='btn-link text-semibold media-heading box-inline'>".$commentrow['UserName']."</a>";
        $text .="     <p class='text-muted text-sm'>".$commentrow['Created']."</p>";
        $text .="   </div>";
        $text .="   <p>".$commentrow['Comment']."</p>";
        $text .="   <div class='pad-ver'>";

        $text .= "<form method='post' action='".prefix."review/replycomment'>";
        $text .= "<ul style='display:none;'>";
        $text .="<textarea class='form-control' type='text' name='reply' placeholder='reply...'></textarea>";
        $text .="<button class='btn btn-primary' type='submit' name='id' value='".$commentrow['Id']."'>svara</button>";
        $text .="</ul>";
        $text .="</form>";

        $text .=" <a class='btn btn-sm btn-default btn-hover-primary toggle-button' style='color: white; float:left;' href='#'>Svara</a>";
        if (str_contains($role,"Admin"))
        {
            $text.= "<form method='post' action='".prefix."comments/edit'><button style='color: white; float:left;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$commentrow['Id']."'>Edit</input>
            </form>";
            $text.= "<form method='post' action='".prefix."comments/delete'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$commentrow['Id']."'>Radera</input>
            </form>";
        }
        if(str_contains($role,"Moderator"))
        {
            $text.= "<form method='post' action='".prefix."comment/flag'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$commentrow['Id']."'>Flagga</input>
            <input type='hidden' name='ReviewId' value='".$commentrow['ReviewId']."' /></form>";
        }
        $text .="</div>";
        $text .="<hr>";

        //REPLIES  
        foreach ($replyArr as $key => $replyrow) 
        {
            if($replyrow['CommentId'] == $commentrow['Id'])
            {      
                $text .="   <div>";
                $text .="     <div class='media-block'>";
                $text .="     <a class='media-left' href='#'><img class='img-circle img-sm' alt='Profile Picture' src='".prefix."img/books/noimage.jpg'></a>";
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
                $text.= "<form method='post' action='".prefix."reply/edit'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$replyrow['ReplyId']."'>Edit</input>
                </form>";
                $text.= "<form method='post' action='".prefix."reply/delete'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$replyrow['ReplyId']."'>Radera</input>
                </form>";
                }
                if(str_contains($role,"Moderator"))
                {
                $text.= "<form method='post' action='".prefix."reply/flag'><button style='color: white;' class='btn btn-sm btn-default btn-hover-primary' type='submit' name='id' value='".$replyrow['ReplyId']."'>Flagga</input>
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
