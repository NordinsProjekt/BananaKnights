<?php
require_once "model/Stats.Model.php";
require_once "classes/Base.Controller.class.php";
class StatsController extends BaseController
{
    private $db;

    function __construct()
    {
        $this->db = new StatsModel();
    }

    function __destruct()
    {
        
    }

    public function GeneralStats()
    {
        $user = $this->GetUserInformation();
        if (str_contains($user['Roles'],"Admin"))
        {
            require_once "views/default.php";
            require_once "views/admin.php";
            $statsArr['Books'] = $this->db->GetNumberOfBooks();
            $statsArr['Authors'] = $this->db->GetNumberOfAuthors();
            $statsArr['Genre'] = $this->db->GetNumberOfGenre();
            $statsArr['Users'] = $this->db->GetNumberOfUsers();
            $statsArr['Reviews'] = $this->db->GetNumberOfReviews();
            $statsArr['Comments'] = $this->db->GetNumberOfComments();
            echo StartPage("Statistik för sidan");
            IndexNav($user['Roles'],$user['Username']);
            echo StatsPanel($statsArr);
            echo EndPage();
        }
        else
        {
            $this->ShowError("Du har inte rätt behörighet");
        }
    }

    
}
?>