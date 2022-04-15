<?php

abstract class BaseController
{
    //abstract protected function GetAll();

    function __destruct()
    {
        
    }

    public function GetUserInformation()
    {
        require_once "model/User.Model.php";
        $userDB = new UserModel();
        if (isset($_SESSION['is_logged_in']) && $_SESSION['UserId']>0)
        {
            $user = $userDB->GetUserRoles($_SESSION['UserId']);
            if (!empty($user))
            {
                $userArr = array (
                    "Username"=>$user['UserName'],
                    "Roles"=>$user['Roles'],
                    "Id"=>$user['Id']
                );
                return $userArr;
            }
        }
        else
        {
            $userArr = array(
                "Username"=>"",
                "Roles"=>""
            );
            return $userArr;
        }
    }

    public function ShowError($errorText) //Sida som visar fel
    {
        $user = $this->GetUserInformation();
        require_once "views/default.php";
        echo StartPage("Fel vid inläsning");
        if (str_contains($user['Roles'],"User"))
        {
            $role = "User";
            if (str_contains($user['Roles'],"Admin"))
            {
                $role = "Admin";
            }
            IndexNav($role,$user['Username']);
            echo "<h1>FEL</h1><p>" . $errorText . "</p>";
            echo EndPage();
        }
        else
        {
            IndexNav("","");
            echo "<h1>FEL</h1><p>" . $errorText . "</p>";
            echo EndPage();
        }
    }

    public function VerifyUserRole($roleName)
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
