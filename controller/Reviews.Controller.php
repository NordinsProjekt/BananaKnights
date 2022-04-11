<?php
require_once "model/Reviews.Model.php";
class ReviewsController
{

    private $db;

    function __construct()
    {
        $this->db = new ReviewsModel();
    }

    function NewReview()
    {
        require_once "views/reviews.php";
        require_once "views/default.php";
        $page = "";
        $page .= StartPage("Skapa ny Review");
        $page .= AddNewReview();
        $page .= EndPage();
        echo $page;
    }

    function AddReview($bookId, $session)
    {
        $arr = array (
            $bookId, $session['UserID'], $_POST['Title'],
            $_POST['Text'], $_POST['Rating'], date("Y-m-d h:i:s")
        );

        $cleanArr = $this->ScrubSaveAuthorArr($arr);

        $result = $this->db->InsertReview($cleanArr);
        if (!$result)
        {
            echo "Review lades till på boken";
        }
        else
        {
            $this->ShowError("Något gick snett i formuläret!");
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
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
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