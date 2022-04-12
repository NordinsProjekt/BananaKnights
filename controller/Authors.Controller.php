<?php
require_once "model/Authors.Model.php";
class AuthorsController
{

    private $db;

    function __construct()
    {
        $this->db = new AuthorsModel();
    }


    function ShowAllAuthors()
    {
        if ($arr = $this->db->GetAllAuthors())
        {
            require_once "views/authors.php";
            require_once "views/default.php";
            $role = "User";
            if ($this->VerifyUserRole("Admin"))
            {
                $role = "Admin";
            }
            $page = "";
            $page .= NavigationPage();
            $page .= StartPage("Visa alla Författare");
            $page .= ShowAllAuthors($arr,$role);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Fel vid inläsning");
            $page .= "<h1>FEL</h1><p>Kunde inte hämta några författare</p>";
            $page .= EndPage();
        }

    }

    function ShowAuthor($id)
    {
        $result = $this->db->GetAuthor($id);
        if ($result)
        {
            require_once "views/authors.php";
            require_once "views/default.php";
            $page = "";
            $page .= NavigationPage();
            $page .= StartPage("Visa Författare");
            $page .= ShowAuthor($result);
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Författaren finns inte");
        }
    }

    function NewAuthor()
    {
        if ($this->VerifyUserRole("Admin"))
        {
            require_once "views/authors.php";
            require_once "views/default.php";
            $page = "";
            $page .= StartPage("Skapa ny Författare");
            $page .= AddNewAuthor();
            $page .= EndPage();
            echo $page;
        }
        else
        {
            $this->ShowError("Inga rättigheter till detta");
        }

    }

    function AddAuthor($session)
    {
        if ($this->VerifyUserRole("Admin"))
        {
            $inputArr = array (
                $_POST['Fname'],$_POST['Lname'],$_POST['Country'], 
                date("Y-m-d h:i:s"),$_POST['Born'],$_POST['Death']
            );
            $cleanArr = $this->ScrubSaveAuthorArr($inputArr);
    
            for($i=0; $i < count($cleanArr); $i++)
            {
                //saknas validering för tom input
                if(is_numeric($cleanArr[$i]))
                {
                    echo "Wrong input! Try again";
                    break;
                }
                else
                {
                    $result = $this->db->InsertAuthor($cleanArr);
                    if (!$result)
                    {
                        echo "Författaren lades till";
                        break;
                    }
                    else
                    {
                        $this->ShowError("Något gick snett i formuläret!");
                        break;
                    }
                }
            }   
        }
        else
        {
            $this->ShowError("Inga rättigheter för detta");
        }
   
    }

    public function EditAuthor($id)
    {

    }

    public function DeleteAuthor($id)
    {

    }

    public function ShowError($errorText)
    {
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Fel vid inläsning");
        $page .= "<h1>FEL</h1><p>" . $errorText . "</p>";
        $page .= EndPage();
        echo $page;
    }

    private function ScrubSaveAuthorArr($arr)
    {
        $cleanArr = array();
        for ($i=0; $i < count($arr); $i++) { 
            $cleanArr[] = $this->CheckUserInputs($arr[$i]);
        }
        return $cleanArr;
    }

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
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