<?php
require_once "model/Home.Model.php";
class HomeController
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
        $role = "";
        if ($this->VerifyUserRole("User"))
        {
            $role = "User";
            require_once "model/User.Model.php";
            $userDB = new UserModel();
            $user = $userDB->GetUserFromId($_SESSION['UserId']);
            if ($this->VerifyUserRole("Admin"))
            {
                $role = "Admin";
            }
            include_once "views/default.php";
            echo StartPage("Cool Books");
            IndexNav($role,$user['UserName']);
            IndexTop();
            IndexCards(); 
            echo EndPage();
            exit();
        }
        else
        {
            include_once "views/default.php";
            echo StartPage("Cool Books");
            IndexNav($role,"");
            IndexTop();
            IndexCards(); 
            echo EndPage();
            exit();
        }

    }

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";"," ","/",",","<",">",")","(","=","[","]","+","*");
      $safe = str_replace($banlist,"",$notsafeText);
      return $safe;
    }

    //Mellanslag tillåtna
    private function CheckUserName($notsafeText)
    {
        $banlist = array("\t",".",";","/",",","<",">",")","(","=","[","]","+","*");
        $safe = str_replace($banlist,"",$notsafeText);
        return $safe;
    }

    private function VerifyUserRole($roleName)
    {
        if (isset($_SESSION['is_logged_in']) && isset($_SESSION['UserId']))
        {
            if ($_SESSION['is_logged_in'] === true && $_SESSION['UserId']>0)
            {
                require_once "model/User.Model.php";
                $userDB = new UserModel();
                if ($userDB->DoesUserHaveRole($roleName,$_SESSION['UserId']) == 1)
                {
                    return true;
                }
            }
        }
        return false;
    }
}
?>