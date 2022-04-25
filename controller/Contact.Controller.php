<?php
require_once "classes/Base.Controller.class.php";
class ContactController extends BaseController
{

    public function ShowContactPage()
    {
        $user = $this->GetUserInformation();
        include_once "views/default.php";
        require_once "views/contact.php";

        echo StartPage("contact");
        echo IndexNav($user['Roles'],$_SESSION['Username']);
        echo contactForm();
        echo EndPage();
    }

}

?>