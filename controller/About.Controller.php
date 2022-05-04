<?php
require_once "classes/Base.Controller.class.php";
class AboutController extends BaseController
{

    public function ShowAboutPage()
    {
        //hämtar användar data
        $user = $this->GetUserInformation();
        include_once "views/default.php";
        require_once "views/about.php";

        echo StartPage("about");
        //om det inte finns någon roll(utloggad) visa standard navbar
        if(empty($user["Roles"])){
            echo IndexNav("","");
        }
        //annars visa hela nav
        else{
            echo IndexNav($user['Roles'],$_SESSION['Username']);
        }
        echo AboutTop();
        echo EndPage();
    }

}
?>