<?php
require_once "classes/Base.Controller.class.php";
class AboutController extends BaseController
{

    public function ShowAboutPage()
    {
        $user = $this->GetUserInformation();
        include_once "views/default.php";
        require_once "views/about.php";

        echo StartPage("about");
        if(empty($user["Roles"])){
            echo IndexNav("","");
        }
        else{
            echo IndexNav($user['Roles'],$_SESSION['Username']);
        }
        echo AboutTop();
        echo EndPage();
    }

}
?>