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
        //TODO Kontrollera behörighet
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
        $hashpassword = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        $user = new User($_POST['Email'],0,$hashpassword,
        NULL,0,0,NULL,0,0,$_POST['Username']);
        if ($user->Validated())
        {
            $result = $this->db->SetUser($user->ToArray());
            
        }
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

    public function Login()
    {
        var_dump($_POST);


    }

    public function Logout()
    {

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
}
?>