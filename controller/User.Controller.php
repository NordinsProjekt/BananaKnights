<?php
require_once "model/User.Model.php";
require_once "classes/User.class.php";

class UserController
{
    private $db;
    function __construct()
    {
        $this->db = new UserModel();
    }

    function __destruct()
    {
        
    }

    public function CreateUser()
    {
        require_once "views/users.php";
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Skapa ny användare");
        $page .= NavigationPage();
        $page .= SignUpForm("");
        $page .= EndPage();
        echo $page;
    }

    public function SaveUser()
    {
        global $prefix;
        //Olika kontroller innan försök att skapa användaren.
        if ($_POST['Password'] != $_POST['ConfirmPassword'])
        {
            $this->ShowError("Lösenord stämmer inte");
            exit();
        }
        if (strlen($_POST['Password']) <=7)
        {
            $this->ShowError("Lösenord måste ha minst 8 tecken");
            exit();
        }
        $result = $this->db->DoesUserExist($_POST['Username']);
        if ($result['NumberOfUsers'] != 0)
        {
            $this->ShowError("Användaren finns redan");
            exit();
        }

        //Hashar lösenordet
        $hashpassword = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        //Skapar ett user objekt för att kontrollera inmatningsdatan
        $user = new User($_POST['Email'],0,$hashpassword,
        NULL,0,0,NULL,0,0,$_POST['Username']);
        //Validering
        if ($user->Validated())
        {
            $result = $this->db->SetUser($user->ToArray());
            if ($result) //Om användaren skapades
            {
                $groupId = $this->db->GetUserGroupID("User");
                $userId = $this->db->GetUserID($user->getUsername(),$user->getPasswordHash());
                //Hämtar information för att lägga användaren i en usergroup.
                if (isset($groupId['Id']) && isset($userId['Id']))
                {
                    $result = $this->db->SetUserGroup($groupId['Id'],$userId['Id']);
                    if ($result)
                    {
                        //Skickar användaren till inloggningssidan
                        header("Location: ". $prefix ."user/loginpage");
                    }
                    else
                    {
                        $this->ShowError("No Roles for you");
                        exit();
                    }
                }
                else
                {
                    $this->ShowError("Kunde inte hitta användarID eller gruppID");
                    exit();
                }
                
            }
            else
            {
                $this->ShowError("Avändaren kunde inte läggas till");
                exit();
            }
            
        }
        else
        {
            $this->ShowError("Validering av UserForm misslyckades");
            exit();
        }
    }

    private function ShowError($errorText) //Sida som visar fel
    {
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Fel vid inläsning");
        $page .= NavigationPage();
        $page .= "<h1>FEL</h1><p>" . $errorText . "</p>";
        $page .= EndPage();
        echo $page;
    }

    private function ShowSuccess($message) //Sida som visas när något går bra.
    {
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Main");
        $page .= NavigationPage();
        $page .= "<h1>Lyckades</h1><p>" . $message . "</p>";
        $page .= EndPage();
        echo $page;
    }

    public function LoginPage()
    {
        require_once "views/users.php";
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Logga in");
        $page .= NavigationPage();
        $page .= LoginForm("");
        $page .= EndPage();
        echo $page;
    }

    public function Login() //Logga in användaren
    {
        $row = $this->db->GetUserFromUsername($this->ScrubInputs($_POST['Username']));
        if (isset($row['Id']))
        {
             if (password_verify($_POST['Password'], $row['PasswordHash']))
             {
                //Användaren har loggat in.
                $_SESSION['is_logged_in'] = TRUE;
                $_SESSION['Username'] = $row['UserName'];
                $_SESSION['UserID'] = $row['Id'];
                //Ladda in homepage
                $this->ShowSuccess("Du loggades in");
             }
             else
             {
                 //Användarnman stämmer men inte lösenord
                 $this->ShowError("Lösenordet är fel");
                 var_dump($_POST);
             }
        }
        else
        {
            //Användarnamn stämmer inte.
            $this->ShowError("Användarnman stämmer inte.");
            var_dump($row);
            var_dump($_POST);
        }         
    }
    private function ShowMainPage()
    {

    }
    public function Logout()
    {
        global $prefix;
        session_destroy();
        header("Location:".$prefix);
    }

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }
}
?>