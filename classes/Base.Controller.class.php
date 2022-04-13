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
                    "Roles"=>$user['Roles']
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

        //return $userArr;
    }

    public function ShowError($errorText) //Sida som visar fel
    {
        $user = $this->GetUserInformation();
        require_once "views/default.php";
        echo StartPage("Fel vid inläsning");
        if (str_contains($user['Roles'],"User"))
        {
            $role = "User";
            require_once "model/User.Model.php";
            $userDB = new UserModel();
            $user = $userDB->GetUserFromId($_SESSION['UserId']);
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
}
?>