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
            $page = "";
            $page .= StartPage("Visa alla Författare");
            $page .= ShowAllAuthors($arr);
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
        require_once "views/authors.php";
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Skapa ny Författare");
        $page .= AddNewAuthor();
        $page .= EndPage();
        echo $page;
    }

    function AddAuthor($session)
    {
        $inputArr = array (
            $_POST['Fname'],$_POST['Lname'],
            $_POST['Country'], date("Y-m-d h:i:s"),$_POST['Born'],$_POST['Death']
        );
        //saknas validering

        $result = $this->db->InsertAuthor($inputArr);
        if (!$result)
        {
            echo "Författaren lades till";
        }
        else
        {
            $this->ShowError("Något gick snett i formuläret!");
        }
        
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

}

?>