<?php

abstract class BaseController
{
    //abstract protected function GetAll();

    function __destruct()
    {
        
    }
    //Används för att kontrollera användaren och hämta rollerna som kontot har.
    protected function GetUserInformation()
    {
        require_once "model/User.Model.php";
        $userDB = new UserModel();
        if (isset($_SESSION['is_logged_in']) && $_SESSION['UserId']>0)
        {
            //Loggar ut användaren och skickar till loginskärmen om
            //något inte stämmer med session
            $this->CheckSession();
            //Om användaren är empty så har den blivit bannad medan session fortfarande är igång.
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
            //Inte inloggad användare
            $userArr = array(
                "Username"=>"",
                "Roles"=>"",
                "Id"=>""
            );
            return $userArr;
        }
    }

    private function CheckSession()
    {
        //Detta är ett litet skydd mot session hijacking
        if ($_SERVER['REMOTE_ADDR'] != $_SESSION['UserIp'])
        {
            session_unset();
            session_destroy();
            header("Location:".prefix."user/loginpage");
            exit();
        }
        
        if ($_SERVER['HTTP_USER_AGENT'] != $_SESSION['UserBrowser'])
        {
            session_unset();
            session_destroy();
            header("Location:".prefix."user/loginpage");
            exit();
        }
    }

    protected function ShowError($errorText) //Sida som visar fel
    {
        //Hämtar den inloggade personen
        $user = $this->GetUserInformation();
        //Loggar händelsen i databasen
        require_once "model/Log.Model.php";
        $logDB = new LogModel();
        $trace = debug_backtrace();
        //Bygger en array med det som skall loggas
        $arr = array (
            $user['Id'],$trace[1]['class'],$trace[1]['function'],$errorText,
            $_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],date("Y-m-d H:i:s")
        );
        $logDB->SetErrorMessage($arr);
        require_once "views/default.php";
        echo StartPage("Fel vid inläsning");
        IndexNav($user['Roles'],$user['Username']);
        echo "<h1>FEL</h1><p>" . $errorText . "</p>";
        echo EndPage();
        exit();
    }

    protected function ScrubFormName($notsafeText)
    {
        $banlist = array("\t"," ","%",";","/","<",">",")","(","=","[","]","+","*","#");
        $safe = trim(str_replace($banlist,"",$notsafeText));
        $safe = stripslashes(htmlspecialchars($safe));
        return $safe;
    }

    protected function GenerateLink()
    {
        $link = "";
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for ($i=0; $i < 20; $i++) 
        { 
            $link .= $characters[rand(0,strlen($characters)-1)];
        }
        $link .= uniqid($link,true);
        return $link;
    }
    
    public function ScrubIndexNumber($notsafeText)
    {
      $banlist = array("\t"," ","%",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      if (is_numeric($safe) && $safe>0)
      {
          return (int)$safe;
      }
      else
      {
          return "";
      }
    }
}
?>
