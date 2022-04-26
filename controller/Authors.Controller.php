<?php
require_once "model/Authors.Model.php";
require_once "classes/Base.Controller.class.php";
require_once "classes/Author.class.php";
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
                IndexNav($user['Roles'],$user['Username']);
                echo ShowAllAuthors($arr,"Admin");
                echo EndPage();
            }
            else
            {
                echo StartPage("Visa alla Författare");
                IndexNav($user['Roles'],$user['Username']);
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
            $dataArr['Author'] = $result;
            $dataArr['Books'] = $this->db->GetAllBooksSortedYear($id);
            $user = $this->GetUserInformation();
            $role = "";
            require_once "views/authors.php";
            require_once "views/default.php";
            if (str_contains($user['Roles'],"Moderator"))
            { $role = "Moderator"; }

            if (str_contains($user['Roles'],"Admin"))
            { $role = "Admin"; }

            echo StartPage("Visa Författare");
            IndexNav($user['Roles'],$user['Username']);
            echo ShowAuthor($dataArr,$role);
            echo EndPage();
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
            IndexNav($user['Roles'],$user['Username']);
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
    //Återställer från flaggat tillstånd
    public function UnFlagAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $fornName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$fornName]['authorId']);
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagAuthor(0,$safe);
            if ($result)
            {
                $this->ShowAllAuthors();
            }
            else
            {
                $this->ShowError("Något gick fel med att återställa författaren");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    //Flaggar för kontroll
    public function FlagAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $formName = $this->ScrubFormName($_POST['formname']);
            $safe = $this->ScrubIndexNumber($_SESSION['form'][$formName]['authorId']);
            unset($_SESSION['form']);
            $result = $this->db->UpdateFlagAuthor(1,$safe);
            if ($result)
            {
                $this->ShowAllAuthors();
            }
            else
            {
                $this->ShowError("Något gick fel med att flagga innehållet");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    public function EditAuthor($id)
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $safe = $this->ScrubIndexNumber($id);
            $author = $this->db->GetAuthor($safe);
            if (!empty($author))
            {
                require_once "views/default.php";
                require_once "views/authors.php";
                echo StartPage("Editera författare"),
                IndexNav($user['Roles'],$user['Username']);
                echo EditAuthor($author,$user['Roles']);
                echo EndPage();
            }
            else
            {
                $this->ShowError("Författaren finns inte");
            }
        }
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    public function UpdateAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            $formname = $this->ScrubFormName($_POST['formname']);
            $authorId = $this->ScrubIndexNumber($_SESSION['form'][$formname]['authorId']);
            $author = new Author($authorId,$_POST['Fname'],$_POST['Lname'],
                    $_POST['Country'],$_POST['Born'],$_POST['Death']);
            unset($_SESSION['form']);
            if ($author->Validated())
            {
                $result = $this->db->UpdateAuthor($author->ToArrayUpdate());
                $this->ShowAllAuthors();
            }
            else
            {
                $this->ShowError("Fel vid validering av data");
            }

        }
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    public function DeleteAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            $result = $this->db->UpdateIsDeletedAuthor(1,$safe);
            if ($result)
            {
                $this->ShowAllAuthors();
            }
            else
            {
                $this->ShowError("Något gick fel med att flagga innehållet");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
    }

    public function UnDeleteAuthor()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Moderator"))
        {
            $safe = $this->ScrubIndexNumber($_POST['id']);
            $result = $this->db->UpdateIsDeletedAuthor(0,$safe);
            if ($result)
            {
                $this->ShowAllAuthors();
            }
            else
            {
                $this->ShowError("Något gick fel med att flagga innehållet");
            }
        }   
        else
        {
            $this->ShowError("Ingen rättighet för detta");
        }
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
      $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }
}
?>