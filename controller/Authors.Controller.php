<?php
require_once "model/Authors.Model.php";
require_once "classes/Base.Controller.class.php";
class AuthorsController extends BaseController
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
            $user = $this->GetUserInformation();
            if (str_contains($user['Roles'],"Admin"))
            {
                echo StartPage("Visa alla Författare");
                IndexNav("Admin",$user['Username']);
                echo ShowAllAuthors($arr,"Admin");
                echo EndPage();
            }
            else
            {
                echo StartPage("Visa alla Författare");
                IndexNav("",$user['Username']);
                echo ShowAllAuthors($arr,"");
                echo EndPage();
            }
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
            $user = $this->GetUserInformation();
            require_once "views/authors.php";
            require_once "views/default.php";
            if (str_contains($user['Roles'],"Admin"))
            {
                echo StartPage("Visa Författare");
                IndexNav("Admin",$user['Username']);
                echo ShowAuthor($result);
                echo EndPage();
            }
            else
            {
                echo StartPage("Visa Författare");
                IndexNav("",$user['Username']);
                echo ShowAuthor($result);
                echo EndPage();
            }
        }
        else
        {
            $this->ShowError("Författaren finns inte");
        }
    }

    function NewAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            require_once "views/authors.php";
            require_once "views/default.php";
            $page = "";
            echo StartPage("Skapa ny Författare");
            IndexNav("Admin",$user['Username']);
            echo AddNewAuthor();
            echo EndPage();

        }
        else
        {
            $this->ShowError("Inga rättigheter till detta");
        }

    }

    function AddAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
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
                        header("Location: ".prefix. "authors/showall");
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
}
?>