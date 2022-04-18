<?php
require_once "model/Home.Model.php";
require_once "classes/Base.Controller.class.php";
class HomeController extends BaseController
{
    private $db;

    function __construct()
    {
        $this->db = new HomeModel();
    }

    function __destruct()
    {
        
    }

    public function ShowHomePage()
    {
        $user = $this->GetUserInformation();
        include_once "views/default.php";
        echo StartPage("Cool Books");
        IndexNav($user['Roles'],$user['Username']);
        IndexTop();
        IndexCards(); 
        echo EndPage();
        // if ($this->VerifyUserRole("User"))
        // {
        //     $role = "User";
        //     require_once "model/User.Model.php";
        //     $userDB = new UserModel();
        //     $user = $userDB->GetUserFromId($_SESSION['UserId']);
        //     if ($this->VerifyUserRole("Admin"))
        //     {
        //         $role = "Admin";
        //     }
        //     include_once "views/default.php";
        //     echo StartPage("Cool Books");
        //     IndexNav($user['Roles'],$user['UserName']);
        //     IndexTop();
        //     IndexCards(); 
        //     echo EndPage();
        //     exit();
        // }
        // else
        // {
        //     include_once "views/default.php";
        //     echo StartPage("Cool Books");
        //     IndexNav($user['Roles'],"");
        //     IndexTop();
        //     IndexCards(); 
        //     echo EndPage();
        //     exit();
        // }
    }
}
?>