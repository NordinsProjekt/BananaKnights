<?php
require_once "model/User.Model.php";
require_once "classes/User.class.php";
require_once "classes/Base.Controller.class.php";

class UserController extends BaseController
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
        echo StartPage("Skapa ny användare");
        IndexNav("","");
        echo SignUpForm("");
        echo EndPage();
    }

    public function SaveUser()
    {
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

        //Lösa detta med en databas kontroll.
        $result = $this->db->DoesUserExist($_POST['Username']);
        if ($result['NumberOfUsers'] != 0)
        {
            $this->ShowError("Användaren finns redan");
            exit();
        }

        $result = $this->db->DoesUserExist($_POST['Email']);
        if ($result['NumberOfUsers'] != 0)
        {
            $this->ShowError("Email är redan registrerad");
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
                        $this->db->SetUserInformation(array(
                            NULL,$userId['Id'],"","","","","","","",date("Y-m-d H:i:s")
                        ));
                        //Skickar användaren till inloggningssidan
                        header("Location: ". prefix ."user/loginpage");
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

    public function LoginPage()
    {
        require_once "views/users.php";
        require_once "views/default.php";
        echo StartPage("Logga in");
        IndexNav("","");
        echo LoginForm();
        echo EndPage();
    }

    public function Login() //Logga in användaren
    {
        $row = $this->db->GetUserFromUsername($this->ScrubInputs($_POST['Username']));
        if (isset($row['Id']))
        {
            if ($row['LockoutEnabled'])
            {
                $this->ShowError("Ditt konto är avstängt, kontakta admin");
                exit();
            }
            if (password_verify($_POST['Password'], $row['PasswordHash']))
            {
                //Användaren har loggat in.
                //Sparar all information som är viktig
                $_SESSION['is_logged_in'] = TRUE;
                $_SESSION['Username'] = $row['UserName'];
                $_SESSION['UserId'] = $row['Id'];
                $_SESSION['UserIp'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['UserBrowser'] = $_SERVER['HTTP_USER_AGENT'];
                //Nollställer antal felinloggningar till 0
                $this->db->UpdateFailedLogin(0,$row['Id']);
                //Ladda in homepage
                header("Location: ".prefix);
            }
            else
            {
                //Användarnman stämmer men inte lösenord
                if ($row['AccessFailedCount'] <3)
                {
                    //Räknar upp antal fel
                    $this->db->UpdateFailedLogin($row['AccessFailedCount']+1,$row['Id']);
                }
                else
                {
                    $this->db->UpdateLockedAccount($row['Id']);
                    $this->ShowError("Ditt konto är nu kontakta admin");
                    exit();
                }
                $this->ShowError("Fel användarnamn/lösenord");
            }
        }
        else
        {
            //Användarnamn stämmer inte.
            $this->ShowError("Fel användarnamn/lösenord");
        }         
    }

    public function UserProfile()
    {
        //Här kan man fylla i sina uppgifter
        //kunna se alla sina reviews och sånt.
    }

    public function ShowProfile($show)
    {
        $safeShow = $this->ScrubInputs($show);
        $user = $this->GetUserInformation();
        if ($user['Roles'] != "")
        {
            require_once "model/Reviews.Model.php";
            require_once "model/Books.Model.php";
            require_once "views/reviews.php";
            require_once "views/default.php";
            require_once "views/users.php";
            $reviewDB = new ReviewsModel();
            $booksDB = new BooksModel();
            $userDetails = $this->db->GetEntireUser($user['Id']);
            $userInfo = $this->db->GetEntireUserInfo($user['Id']);
            //Hanterar om det inte finns någon information sparad i databasen
            if (!$userInfo)
            {
                $userInfo['City'] = "N/A";
                $userInfo['Address'] = "N/A";
                $userInfo['PostalCode'] = "N/A";
            }
            $window;
            switch($safeShow)
            {
                case "userinfo":
                    $window['WindowTitle'] = "<h2 class='boxTitle'>Personuppgifter</h2>";
                    $window['Body'] = UserInformationForm($user,$userInfo);
                    break;
                case "readlist":
                    $window['WindowTitle'] = "<h2 class='boxTitle'>Dina lästa böcker</h2>";
                    $result = $booksDB->GetAllRecommendedBooksByUser($user['Id']);
                    if ($result)
                    {
                        $window['Body'] = IndexCardsProfile($result);
                    }
                    else
                    {
                        $window['Body'] = "<p>Fanns inga lästa böcker</p>";
                    }
                    
                    break;
                case "reviews":
                    $window['WindowTitle'] = "<h2 class='boxTitle'>Dina recensioner</h2>";
                    $result = $reviewDB->GetAllUserReviews($user['Id']);
                    if ($result)
                    {
                        $window['Body'] = ShowUserReviews($result);
                    }
                    else
                    {
                        $window['Body'] = "<p>Fanns inga recensioner</p>";
                    }
                    
                    break;
                default:
                    $window['WindowTitle'] = "<h2 class='boxTitle'>Welcome Back ".$userDetails['UserName']."</h2>";
                    $window['Body'] = "";
                    break;
            }
            require_once "views/users.php";
            require_once "views/default.php";
            echo StartPage("Profil");
            echo IndexNav($user['Roles'],$user['Username']);
            echo Profile($user, $userDetails, $userInfo, $window);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du måste vara inloggad");
        }

    }
    public function UpdateUserInfo($userId)
    {
        $user = $this->GetUserInformation();
        $safe = $this->ScrubIndexNumber($userId);
        //Samma användare som skickar formuläret som är inloggad
        if ($safe == $user['Id'])
        {
            $arr = array(
                $_POST['firstname'],$_POST['lastname'],$_POST['phone'],$_POST['address'],
                $_POST['address2'],$_POST['postalcode'],$_POST['city'],$user['Id']
            );
            $arr = $this->ScrubText($arr);
            $result = $this->db->UpdateUserInput($arr);
            if ($result)
            {
                $this->ShowProfile("");
            }
            else
            {
                $this->ShowError("Kunde inte spara personuppgifter");
            }

        }
        else
        {

        }
    }

    public function Logout()
    {
        session_unset();
        session_destroy();
        header("Location:".prefix);
    }

    private function ScrubText($arr)
    {
        $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*");
        foreach($arr as $key => $value) 
        {
            $safe = str_replace($banlist,"",$value);
            $value = $safe;
        }
        return $arr;
    }

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }
}
?>