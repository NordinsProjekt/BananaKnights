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
            $statsArr['Spammer'] = $this->db->UserWithMostComments();
            echo StartPage("Statistik för sidan");
            IndexNav($user['Roles'],$user['Username']);
            echo StatsPanel($statsArr);
            echo EndPage();
            //Test för strukturen
            // $chartArr = array("labels"=>"['Totalt','Senaste(7)']");
            // $chartArr += array("label"=>"'Kommentarer'");
            // $chartArr += array("data"=>"[7,3]");
            // $this->APICharts($chartArr,"bar");
            //Skriver ut den totala aktiviteten på sidan
            $chartArr = array("labels"=>"['Books','Authors','Genre','Users','Reviews','Comments']");
            $chartArr += array("label"=>"'Aktiviteter'");
            $chartArr += array("data"=>"[".$statsArr['Books']['NumberofBooks'].",".$statsArr['Authors']['NumberofAuthors'].",".$statsArr['Genre']['NumberofGenre'].","
            .$statsArr['Users']['NumberofUsers'].",".$statsArr['Reviews']['NumberofReviews'].",".$statsArr['Comments']['NumberofComments']."]");
            $this->APICharts($chartArr,"bar");
        }
        else
        {
            $this->ShowError("Du har inte rätt behörighet");
        }
    }

    public function APICharts($statsArr,$typ)
    {
        //Bygger charten
        $text = "{type:'".$typ."',data:{labels:".$statsArr['labels'].",datasets:[{label:".$statsArr['label'].",data:".$statsArr['data']."}]}}";      
        //API biblotek som QuickChart rekommenderade
        require_once "classes/QuickChart.php";
        $qc = new QuickChart();
        $qc->setConfig($text);
        // Visa bilden
        echo '<img width="500" height="300" src="data:image/png;base64,' . base64_encode($qc->toBinary()) . '" />';
    }
}
?>